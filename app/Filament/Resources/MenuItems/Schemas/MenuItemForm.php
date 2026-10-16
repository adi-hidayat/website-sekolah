<?php

namespace App\Filament\Resources\MenuItems\Schemas;

use App\Enums\MenuItemType;
use App\Models\MenuItem;
use App\Models\Page;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class MenuItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Item Menu')
                    ->schema([
                        Select::make('menu_id')
                            ->label('Menu')
                            ->relationship('menu', 'name')
                            ->required()
                            ->live(),
                        Select::make('parent_id')
                            ->label('Induk')
                            ->options(fn (Get $get, ?MenuItem $record): array => self::parentOptions($get, $record))
                            ->searchable()
                            ->placeholder('— Tanpa induk —')
                            ->helperText('Kosongkan untuk item utama.'),
                        TextInput::make('label')
                            ->label('Label')
                            ->required()
                            ->maxLength(255),
                        Select::make('type')
                            ->label('Jenis')
                            ->options(MenuItemType::options())
                            ->default(MenuItemType::CustomUrl->value)
                            ->required()
                            ->live(),
                        Select::make('page_id')
                            ->label('Halaman CMS')
                            ->relationship('page', 'title', modifyQueryUsing: fn (Builder $query) => $query->orderBy('title'))
                            ->getOptionLabelFromRecordUsing(fn (Page $record): string => trim($record->title.' (/'.$record->slug.')'))
                            ->searchable(['title', 'slug'])
                            ->preload()
                            ->visible(fn (Get $get): bool => self::is($get, MenuItemType::Page))
                            ->required(fn (Get $get): bool => self::is($get, MenuItemType::Page)),
                        Select::make('department_id')
                            ->label('Jurusan')
                            ->relationship('department', 'name', modifyQueryUsing: fn (Builder $query) => $query->orderBy('sort_order'))
                            ->searchable()
                            ->preload()
                            ->visible(fn (Get $get): bool => self::is($get, MenuItemType::Department))
                            ->required(fn (Get $get): bool => self::is($get, MenuItemType::Department)),
                        Select::make('news_id')
                            ->label('Berita')
                            ->relationship('news', 'title', modifyQueryUsing: fn (Builder $query) => $query->latest('published_at'))
                            ->searchable()
                            ->preload()
                            ->placeholder('Halaman indeks berita')
                            ->helperText('Kosongkan untuk mengarah ke daftar berita.')
                            ->visible(fn (Get $get): bool => self::is($get, MenuItemType::News)),
                        Select::make('announcement_id')
                            ->label('Pengumuman')
                            ->relationship('announcement', 'title', modifyQueryUsing: fn (Builder $query) => $query->latest())
                            ->searchable()
                            ->preload()
                            ->placeholder('Halaman indeks pengumuman')
                            ->helperText('Kosongkan untuk mengarah ke daftar pengumuman.')
                            ->visible(fn (Get $get): bool => self::is($get, MenuItemType::Announcement)),
                        Select::make('gallery_id')
                            ->label('Galeri')
                            ->relationship('gallery', 'title', modifyQueryUsing: fn (Builder $query) => $query->latest())
                            ->searchable()
                            ->preload()
                            ->placeholder('Halaman indeks galeri')
                            ->helperText('Kosongkan untuk mengarah ke daftar galeri.')
                            ->visible(fn (Get $get): bool => self::is($get, MenuItemType::Gallery)),
                        TextInput::make('target_url')
                            ->label('URL')
                            ->placeholder('/spmb atau https://contoh.com')
                            ->helperText('Boleh path relatif (mis. /spmb) atau URL lengkap http(s).')
                            ->visible(fn (Get $get): bool => self::is($get, MenuItemType::CustomUrl))
                            ->required(fn (Get $get): bool => self::is($get, MenuItemType::CustomUrl))
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Tampilan')
                    ->schema([
                        Select::make('target')
                            ->label('Target')
                            ->options(['_self' => 'Tab yang sama', '_blank' => 'Tab baru'])
                            ->default('_self')
                            ->required(),
                        Toggle::make('is_visible')
                            ->label('Tampilkan')
                            ->default(true)
                            ->required(),
                        TextInput::make('sort_order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0)
                            ->required(),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),
            ]);
    }

    protected static function is(Get $get, MenuItemType $type): bool
    {
        return $get('type') === $type->value;
    }

    /**
     * Item induk dibatasi pada menu yang sama dan tidak boleh dirinya sendiri.
     *
     * @return array<int, string>
     */
    protected static function parentOptions(Get $get, ?MenuItem $record): array
    {
        $menuId = $get('menu_id');

        if ($menuId === null) {
            return [];
        }

        return MenuItem::query()
            ->where('menu_id', $menuId)
            ->when($record, fn (Builder $query) => $query->whereKeyNot($record->getKey()))
            ->orderBy('label')
            ->pluck('label', 'id')
            ->all();
    }
}
