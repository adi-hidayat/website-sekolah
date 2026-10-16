<?php

namespace App\Filament\Resources\MenuItems\Tables;

use App\Enums\MenuItemType;
use App\Models\MenuItem;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class MenuItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('label')
                    ->label('Label')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Jenis')
                    ->badge()
                    ->state(fn (MenuItem $record): string => $record->type->label()),
                TextColumn::make('menu.name')
                    ->label('Menu')
                    ->badge(),
                TextColumn::make('parent.label')
                    ->label('Induk')
                    ->placeholder('—')
                    ->toggleable(),
                TextColumn::make('resolved_url')
                    ->label('Tujuan')
                    ->state(fn (MenuItem $record): ?string => $record->resolvedUrl())
                    ->limit(45)
                    ->placeholder('—')
                    ->tooltip(fn (MenuItem $record): ?string => $record->resolvedUrl()),
                IconColumn::make('is_visible')
                    ->label('Tampil')
                    ->boolean(),
                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->numeric()
                    ->sortable(),
            ])
            ->defaultSort('menu_id')
            ->filters([
                SelectFilter::make('menu_id')
                    ->label('Menu')
                    ->relationship('menu', 'name'),
                SelectFilter::make('type')
                    ->label('Jenis')
                    ->options(MenuItemType::options()),
                TernaryFilter::make('is_visible')
                    ->label('Tampil'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
