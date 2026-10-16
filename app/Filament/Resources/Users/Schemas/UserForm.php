<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rules\Password;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Akun')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        TextInput::make('password')
                            ->label('Kata Sandi')
                            ->password()
                            ->revealable()
                            ->confirmed()
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->saved(fn (?string $state): bool => filled($state))
                            ->rule(Password::default())
                            ->helperText('Biarkan kosong saat mengedit bila kata sandi tidak ingin diubah.'),
                        TextInput::make('password_confirmation')
                            ->label('Konfirmasi Kata Sandi')
                            ->password()
                            ->revealable()
                            ->dehydrated(false)
                            ->requiredWith('password'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
