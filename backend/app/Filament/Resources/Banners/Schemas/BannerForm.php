<?php

namespace App\Filament\Resources\Banners\Schemas;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class BannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->maxLength(255)
                    ->helperText('Internal label only — not shown on the site.'),
                TextInput::make('link_url')
                    ->label('Link URL')
                    ->maxLength(255)
                    ->helperText('e.g. /collections/necklace-sets'),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->integer()
                    ->minValue(0)
                    ->default(0),
                Toggle::make('is_active')
                    ->required()
                    ->default(true),
                SpatieMediaLibraryFileUpload::make('image')
                    ->collection('image')
                    ->conversion('mobile')
                    ->image()
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
