<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Order')
                    ->columns(3)
                    ->schema([
                        TextInput::make('order_number')
                            ->disabled(),
                        Select::make('status')
                            ->options([
                                'placed' => 'Placed',
                                'processing' => 'Processing',
                                'shipped' => 'Shipped',
                                'delivered' => 'Delivered',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required(),
                        TextInput::make('payment_method')
                            ->disabled(),
                    ]),

                Section::make('Customer')
                    ->columns(3)
                    ->schema([
                        TextInput::make('customer_name')->disabled(),
                        TextInput::make('customer_email')->disabled(),
                        TextInput::make('customer_phone')->disabled(),
                    ]),

                Section::make('Shipping Address')
                    ->columns(2)
                    ->schema([
                        TextInput::make('shipping_address_line1')->label('Address Line 1')->disabled(),
                        TextInput::make('shipping_address_line2')->label('Address Line 2')->disabled(),
                        TextInput::make('shipping_city')->label('City')->disabled(),
                        TextInput::make('shipping_state')->label('State')->disabled(),
                        TextInput::make('shipping_postal_code')->label('Postal Code')->disabled(),
                        TextInput::make('shipping_country')->label('Country')->disabled(),
                    ]),

                Section::make('Order Note')
                    ->visible(fn ($record) => filled($record?->order_note))
                    ->schema([
                        Textarea::make('order_note')->disabled()->columnSpanFull(),
                    ]),

                Section::make('Items')
                    ->schema([
                        Repeater::make('items')
                            ->relationship('items')
                            ->disabled()
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false)
                            ->columns(4)
                            ->schema([
                                TextInput::make('product_title')->label('Product'),
                                TextInput::make('sku')->label('SKU'),
                                TextInput::make('quantity')->numeric(),
                                TextInput::make('subtotal')->numeric()->prefix('₹'),
                            ]),
                    ]),

                Section::make('Totals')
                    ->columns(3)
                    ->schema([
                        TextInput::make('subtotal')->numeric()->prefix('₹')->disabled(),
                        TextInput::make('shipping_fee')->numeric()->prefix('₹')->disabled(),
                        TextInput::make('total')->numeric()->prefix('₹')->disabled(),
                    ]),
            ]);
    }
}
