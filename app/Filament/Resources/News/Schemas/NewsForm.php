<?php

namespace App\Filament\Resources\News\Schemas;

use App\Enums\NewsStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class NewsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('News')
                    ->tabs([
                        Tab::make('Konten')
                            ->schema([
                                TextInput::make('title')
                                    ->required(),
                                TextInput::make('slug')
                                    ->required(),
                                Textarea::make('excerpt')
                                    ->label('Ringkasan')
                                    ->helperText('Teks polos untuk kartu berita dan meta deskripsi.')
                                    ->columnSpanFull(),
                                RichEditor::make('content')
                                    ->label('Isi Berita')
                                    ->required()
                                    ->fileAttachmentsDisk('public')
                                    ->fileAttachmentsDirectory('news/content')
                                    ->columnSpanFull(),
                                FileUpload::make('featured_image')
                                    ->disk('public')
                                    ->image(),
                                TextInput::make('gallery'),
                            ])
                            ->columns(2),
                        Tab::make('Publikasi')
                            ->schema([
                                Select::make('author_id')
                                    ->relationship('author', 'name'),
                                DateTimePicker::make('published_at'),
                                Select::make('status')
                                    ->options(NewsStatus::class)
                                    ->default('draft')
                                    ->required(),
                                TextInput::make('views_count')
                                    ->required()
                                    ->numeric()
                                    ->default(0),
                            ])
                            ->columns(2),
                        Tab::make('SEO')
                            ->schema([
                                TextInput::make('seo_title'),
                                TextInput::make('canonical_url')
                                    ->url(),
                                Textarea::make('seo_description')
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
