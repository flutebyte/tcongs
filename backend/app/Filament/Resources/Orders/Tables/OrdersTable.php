<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('order_number')
                    ->label('Order #')
                    ->searchable(),
                TextColumn::make('customer_name')
                    ->searchable(),
                TextColumn::make('customer_email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('total')
                    ->money('inr')
                    ->sortable(),
                TextColumn::make('payment_method')
                    ->badge(),
                TextColumn::make('payment_status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'paid' => 'success',
                        'pending' => 'warning',
                        'failed' => 'danger',
                        'refunded' => 'gray',
                        'partially_refunded' => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'placed' => 'info',
                        'packed' => 'warning',
                        'shipped' => 'primary',
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                        'returned' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->label('Placed')
                    ->dateTime('d M Y, h:i A')
                    ->sortable(),
                // Customer-submitted via account.orders.cancellation-request (see
                // AccountController) — a flag for admin review, not a status
                // change; the actual cancel/return transition still happens via
                // the normal EditAction/status field below, guarded by
                // Order::ALLOWED_TRANSITIONS as always.
                IconColumn::make('cancellation_requested_at')
                    ->label('Cancel/Return Req.')
                    ->boolean()
                    ->tooltip(fn (Order $record) => $record->cancellation_requested_at
                        ? "Requested {$record->cancellation_requested_at->format('d M Y')}: {$record->cancellation_reason}"
                        : null),
            ])
            ->filters([
                Filter::make('cancellation_requested')
                    ->label('Has cancellation/return request')
                    ->query(fn (Builder $query) => $query->whereNotNull('cancellation_requested_at')),
                SelectFilter::make('status')
                    ->options([
                        'placed' => 'Placed',
                        'packed' => 'Packed',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                        'returned' => 'Returned',
                    ]),
                SelectFilter::make('payment_status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'failed' => 'Failed',
                        'refunded' => 'Refunded',
                        'partially_refunded' => 'Partially Refunded',
                    ]),
            ])
            ->recordActions([
                Action::make('invoice')
                    ->label('Invoice')
                    ->icon(Heroicon::OutlinedDocumentArrowDown)
                    ->color('gray')
                    ->action(fn (Order $record) => self::streamInvoice($record)),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('cancel')
                        ->label('Cancel selected')
                        ->icon(Heroicon::OutlinedXCircle)
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalDescription('Cancels every selected order that is still cancellable and restocks its items. Orders already shipped/delivered/cancelled/returned are left untouched.')
                        ->action(function (Collection $records) {
                            $cancelled = 0;
                            foreach ($records as $record) {
                                if (in_array('cancelled', Order::ALLOWED_TRANSITIONS[$record->status] ?? [], true)) {
                                    $record->update(['status' => 'cancelled']);
                                    $cancelled++;
                                }
                            }

                            Notification::make()
                                ->title("Cancelled {$cancelled} of {$records->count()} selected order(s).")
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }

    public static function streamInvoice(Order $record)
    {
        $record->loadMissing('items');

        return response()->streamDownload(function () use ($record) {
            echo Pdf::loadView('orders.invoice', ['order' => $record])->output();
        }, "invoice-{$record->order_number}.pdf");
    }
}
