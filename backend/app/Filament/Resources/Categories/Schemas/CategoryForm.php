<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->alphaDash()
                    ->unique(ignoreRecord: true),
                Textarea::make('description')
                    ->columnSpanFull(),
                Select::make('parent_id')
                    ->relationship('parent', 'name'),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->integer()
                    ->minValue(0)
                    ->default(0),

                SpatieMediaLibraryFileUpload::make('image')
                    ->collection('image')
                    ->conversion('tile')
                    ->image()
                    ->columnSpanFull(),

                Section::make('SEO')
                    ->description('Optional — leave blank to use the site defaults.')
                    ->relationship('seoMeta')
                    ->schema([
                        TextInput::make('title')
                            ->label('Meta title'),
                        Textarea::make('description')
                            ->label('Meta description'),
                        TextInput::make('og_image')
                            ->label('OG image URL'),
                        TextInput::make('canonical')
                            ->label('Canonical URL'),
                    ])
                    ->collapsible()
                    ->collapsed()
                    ->columnSpanFull(),
            ]);
    }
}
