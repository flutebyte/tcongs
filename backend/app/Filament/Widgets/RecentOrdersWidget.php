<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class RecentOrdersWidget extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    protected function getTableHeading(): string
    {
        return 'Recent Orders';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Order::query()->latest())
            ->defaultPaginationPageOption(5)
            ->columns([
                TextColumn::make('order_number')
                    ->label('Order #')
                    ->searchable(),
                TextColumn::make('customer_name')
                    ->searchable(),
                TextColumn::make('total')
                    ->money('inr'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'placed' => 'info',
                        'processing' => 'warning',
                        'shipped' => 'primary',
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->label('Placed')
                    ->dateTime('d M Y, h:i A')
                    ->sortable(),
            ]);
    }
}
