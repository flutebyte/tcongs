<?php

namespace App\Filament\Resources\HomepageBlocks\Schemas;

use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class HomepageBlockForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type')
                    ->required()
                    ->options([
                        'product_carousel' => 'Product carousel',
                        'collection_carousel' => 'Collection grid',
                        'celebrities' => 'Celebrities / As Seen On',
                        'promo_banner' => 'Promo banner grid',
                        'testimonials' => 'Testimonials',
                        'usp' => 'USP / trust badges',
                        'brand_story' => 'Brand story',
                        'newsletter' => 'Newsletter signup',
                    ])
                    ->native(false),
                TextInput::make('title')
                    ->maxLength(255),
                TextInput::make('subtitle')
                    ->maxLength(255),
                TextInput::make('cta_label')
                    ->label('CTA label')
                    ->maxLength(255),
                TextInput::make('cta_url')
                    ->label('CTA URL')
                    ->maxLength(255),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->integer()
                    ->minValue(0)
                    ->default(0),
                Toggle::make('is_active')
                    ->required()
                    ->default(true),

                Section::make('Items')
                    ->description('Testimonials, USP points, promo tiles, or products for a product carousel.')
                    ->schema([
                        Repeater::make('items')
                            ->relationship('items')
                            ->orderColumn('sort_order')
                            ->schema([
                                TextInput::make('title')
                                    ->maxLength(255),
                                Textarea::make('body')
                                    ->rows(2),
                                TextInput::make('link_url')
                                    ->label('Link URL')
                                    ->maxLength(255),
                                TextInput::make('rating')
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(5)
                                    ->helperText('1-5, for testimonials only'),
                                Select::make('itemable_type')
                                    ->label('Link to')
                                    ->options([
                                        Product::class => 'Product',
                                        Category::class => 'Category',
                                        Collection::class => 'Collection',
                                    ])
                                    ->native(false)
                                    ->live(),
                                Select::make('itemable_id')
                                    ->label('Record')
                                    ->options(function (\Filament\Schemas\Components\Utilities\Get $get) {
                                        return match ($get('itemable_type')) {
                                            Product::class => Product::query()->pluck('title', 'id'),
                                            Category::class => Category::query()->pluck('name', 'id'),
                                            Collection::class => Collection::query()->pluck('name', 'id'),
                                            default => [],
                                        };
                                    })
                                    ->searchable()
                                    ->visible(fn (\Filament\Schemas\Components\Utilities\Get $get) => filled($get('itemable_type'))),
                                SpatieMediaLibraryFileUpload::make('image')
                                    ->collection('image')
                                    ->conversion('card')
                                    ->image()
                                    ->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->columnSpanFull()
                            ->collapsible(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
