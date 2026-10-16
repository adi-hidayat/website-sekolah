<?php

namespace App\Filament\Resources\Teachers\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TeacherForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Guru')
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('nip'),
                        TextInput::make('position'),
                        TextInput::make('subject'),
                        TextInput::make('photo'),
                        Select::make('department_id')
                            ->relationship('department', 'name'),
                        Textarea::make('bio')
                            ->columnSpanFull(),
                        TextInput::make('sort_order')
                            ->required()
                            ->numeric()
                            ->default(0),
                        Toggle::make('is_active')
                            ->required(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
