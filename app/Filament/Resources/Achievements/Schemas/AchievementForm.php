<?php

namespace App\Filament\Resources\Achievements\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class AchievementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Achievement')
                    ->tabs([
                        Tab::make('Informasi')
                            ->schema([
                                TextInput::make('title')
                                    ->required(),
                                TextInput::make('slug')
                                    ->required(),
                                TextInput::make('category'),
                                TextInput::make('level'),
                                TextInput::make('rank'),
                                DatePicker::make('achieved_at'),
                                Select::make('department_id')
                                    ->relationship('department', 'name'),
                                FileUpload::make('image')
                                    ->disk('public')
                                    ->image(),
                            ])
                            ->columns(2),
                        Tab::make('Publikasi')
                            ->schema([
                                Textarea::make('description'),
                                Toggle::make('is_published')
                                    ->required(),
                            ])
                            ->columns(1),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
