<?php

namespace App\Filament\Resources\Departments\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class DepartmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Department')
                    ->tabs([
                        Tab::make('Umum')
                            ->schema([
                                TextInput::make('name')
                                    ->required(),
                                TextInput::make('code'),
                                TextInput::make('slug')
                                    ->required(),
                                TextInput::make('status')
                                    ->required()
                                    ->default('draft'),
                                TextInput::make('sort_order')
                                    ->required()
                                    ->numeric()
                                    ->default(0),
                                TextInput::make('thumbnail'),
                                TextInput::make('banner'),
                                TextInput::make('competencies'),
                            ])
                            ->columns(2),
                        Tab::make('Deskripsi')
                            ->schema([
                                Textarea::make('short_description'),
                                Textarea::make('description'),
                            ])
                            ->columns(1),
                        Tab::make('SEO')
                            ->schema([
                                TextInput::make('seo_title'),
                                Textarea::make('seo_description'),
                            ])
                            ->columns(1),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
