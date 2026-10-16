<?php

namespace App\Filament\Resources\Announcements\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class AnnouncementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Announcement')
                    ->tabs([
                        Tab::make('Konten')
                            ->schema([
                                TextInput::make('title')
                                    ->required(),
                                TextInput::make('slug')
                                    ->required(),
                                TextInput::make('category'),
                                TextInput::make('attachment'),
                                Textarea::make('content')
                                    ->required()
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),
                        Tab::make('Jadwal & Status')
                            ->schema([
                                TextInput::make('status')
                                    ->required()
                                    ->default('draft'),
                                TextInput::make('sort_order')
                                    ->required()
                                    ->numeric()
                                    ->default(0),
                                DatePicker::make('starts_at'),
                                DatePicker::make('ends_at'),
                                Toggle::make('is_pinned')
                                    ->required(),
                                TextInput::make('created_by')
                                    ->numeric(),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
