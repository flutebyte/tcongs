<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Filament\Resources\Orders\Tables\OrdersTable;
use App\Models\Order;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('invoice')
                ->label('Download Invoice')
                ->icon(Heroicon::OutlinedDocumentArrowDown)
                ->color('gray')
                ->action(fn (Order $record) => OrdersTable::streamInvoice($record)),

            Action::make('refund')
                ->label('Refund')
                ->icon(Heroicon::OutlinedArrowUturnLeft)
                ->color('danger')
                ->visible(fn (Order $record) => in_array($record->payment_status, ['paid', 'partially_refunded'], true))
                ->schema([
                    TextInput::make('amount')
                        ->label('Refund amount (₹)')
                        ->numeric()
                        ->required()
                        ->minValue(0.01),
                    Textarea::make('reason')
                        ->label('Reason')
                        ->required(),
                ])
                ->action(function (array $data, Order $record) {
                    $alreadyRefunded = (float) ($record->refunded_amount ?? 0);
                    $maxRefundable = (float) $record->total - $alreadyRefunded;
                    $amount = (float) $data['amount'];

                    if ($amount > $maxRefundable) {
                        Notification::make()
                            ->title('Refund amount exceeds the remaining refundable balance (₹'.number_format($maxRefundable, 2).').')
                            ->danger()
                            ->send();

                        return;
                    }

                    $newRefunded = $alreadyRefunded + $amount;

                    $record->update([
                        'refunded_amount' => $newRefunded,
                        'refund_reason' => $data['reason'],
                        'payment_status' => $newRefunded >= (float) $record->total ? 'refunded' : 'partially_refunded',
                    ]);

                    Notification::make()
                        ->title('Refund recorded.')
                        ->success()
                        ->send();
                }),
        ];
    }
}
