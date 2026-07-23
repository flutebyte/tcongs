<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\Collection;
use App\Models\Order;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StoreStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $revenue = Order::where('status', '!=', 'cancelled')->sum('total');
        $ordersToday = Order::whereDate('created_at', today())->count();
        $lowStock = Product::where('is_active', true)->where('stock_quantity', '<=', 5)->count();

        return [
            Stat::make('Revenue', '₹' . number_format((float) $revenue, 2))
                ->description('Across all non-cancelled orders')
                ->color('success'),

            Stat::make('Orders', (string) Order::count())
                ->description($ordersToday . ' placed today')
                ->color('info'),

            Stat::make('Products', (string) Product::count())
                ->description(Product::where('is_active', true)->count() . ' active')
                ->color('primary'),

            Stat::make('Low Stock', (string) $lowStock)
                ->description('Active products with ≤5 in stock')
                ->color($lowStock > 0 ? 'danger' : 'success'),

            Stat::make('Categories', (string) Category::count())
                ->color('gray'),

            Stat::make('Collections', (string) Collection::count())
                ->color('gray'),
        ];
    }
}
