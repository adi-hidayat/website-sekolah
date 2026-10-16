<?php

namespace App\Filament\Resources\Pages\Schemas;

use App\Enums\PageStatus;
use App\Enums\PageTemplate;
use App\Support\PageSections;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make('Page')
                ->tabs([
                    Tab::make('Konten')->schema([
                        TextInput::make('title')
                            ->required()->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, $set, $get) => $get('slug') === null || $get('slug') === '' ? $set('slug', Str::slug((string) $state)) : null),
                        TextInput::make('slug')
                            ->required()->maxLength(255)->alphaDash()
                            ->unique(ignoreRecord: true),
                        Textarea::make('excerpt')->columnSpanFull()->rows(2),
                        RichEditor::make('content')->columnSpanFull()
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('pages/content')
                            ->fileAttachmentsVisibility('public'),
                        FileUpload::make('featured_image')->image()->disk('public')->directory('pages')->visibility('public')->maxSize(2048)->columnSpanFull(),
                    ])->columns(2),

                    Tab::make('Page Builder')->schema([
                        Builder::make('sections')
                            ->label('Sections')
                            ->columnSpanFull()
                            ->collapsible()->cloneable()->reorderableWithButtons()
                            ->blocks([
                                Builder\Block::make('hero')->label('Hero')->schema([
                                    TextInput::make('eyebrow')->label('Eyebrow'),
                                    TextInput::make('title')->label('Judul')->required(),
                                    Textarea::make('description')->label('Deskripsi')->rows(2)->columnSpanFull(),
                                    FileUpload::make('image')->label('Gambar')->image()->disk('public')->directory('pages/hero')->visibility('public')->maxSize(2048)->columnSpanFull(),
                                    TextInput::make('cta.label')->label('Label CTA'),
                                    TextInput::make('cta.url')->label('URL CTA'),
                                    TextInput::make('secondary.label')->label('Label CTA sekunder'),
                                    TextInput::make('secondary.url')->label('URL CTA sekunder'),
                                ])->columns(2),
                                Builder\Block::make('principal')->label('Sambutan Kepala Sekolah')->schema([
                                    TextInput::make('eyebrow')->label('Eyebrow'),
                                    TextInput::make('title')->label('Judul'),
                                    TextInput::make('name')->label('Nama'),
                                    TextInput::make('position')->label('Jabatan'),
                                    FileUpload::make('photo')->label('Foto')->image()->disk('public')->directory('pages/principal')->visibility('public')->maxSize(2048)->columnSpanFull(),
                                    RichEditor::make('message')->label('Sambutan')->columnSpanFull()
                                        ->fileAttachmentsDisk('public')
                                        ->fileAttachmentsDirectory('pages/content')
                                        ->fileAttachmentsVisibility('public'),
                                    TextInput::make('cta_label')->label('Label CTA'),
                                    TextInput::make('cta_url')->label('URL CTA'),
                                ])->columns(2),
                                Builder\Block::make('rich_text')->label('Rich Text')->schema([
                                    RichEditor::make('html')->label('Konten')->columnSpanFull()
                                        ->fileAttachmentsDisk('public')
                                        ->fileAttachmentsDirectory('pages/content')
                                        ->fileAttachmentsVisibility('public'),
                                ]),
                                Builder\Block::make('image')->label('Image')->schema([
                                    FileUpload::make('src')->label('Gambar')->image()->disk('public')->directory('pages/images')->visibility('public')->maxSize(2048)->required()->columnSpanFull(),
                                    TextInput::make('caption')->label('Caption'),
                                    Select::make('alignment')->options(['left' => 'Kiri', 'center' => 'Tengah', 'right' => 'Kanan'])->default('center'),
                                ])->columns(2),
                                Builder\Block::make('image_gallery')->label('Image Gallery')->schema([
                                    FileUpload::make('images')->label('Gambar')->multiple()->reorderable()->image()->disk('public')->directory('pages/galleries')->visibility('public')->maxSize(2048)->columnSpanFull(),
                                ])->columns(1),
                                Builder\Block::make('card_grid')->label('Card Grid')->schema([
                                    TextInput::make('title')->label('Judul')->columnSpanFull(),
                                    Textarea::make('cards')->label('Cards (JSON sederhana)')->rows(3)->columnSpanFull()
                                        ->formatStateUsing(fn (mixed $state): ?string => PageSections::editableText($state)),
                                ])->columns(1),
                                Builder\Block::make('statistics')->label('Statistics')->schema([
                                    Textarea::make('items')->label('Items (JSON: label,value)')->rows(3)->columnSpanFull()
                                        ->formatStateUsing(fn (mixed $state): ?string => PageSections::editableText($state)),
                                ])->columns(1),
                                Builder\Block::make('values')->label('Nilai & Karakter')->schema([
                                    TextInput::make('title')->label('Judul')->default('Nilai dan Karakter Islami')->columnSpanFull(),
                                    Textarea::make('description')->label('Deskripsi')->rows(2)->columnSpanFull(),
                                    Textarea::make('items')->label('Items (JSON: icon,title,description)')->rows(4)->columnSpanFull()
                                        ->formatStateUsing(fn (mixed $state): ?string => PageSections::editableText($state)),
                                ])->columns(1),
                                Builder\Block::make('achievements')->label('Achievements')->schema([
                                    TextInput::make('title')->label('Judul')->default('Prestasi'),
                                    TextInput::make('limit')->label('Limit')->numeric()->default(6),
                                    Textarea::make('description')->label('Deskripsi')->rows(2)->columnSpanFull(),
                                ])->columns(2),
                                Builder\Block::make('facilities')->label('Facilities')->schema([
                                    TextInput::make('title')->label('Judul')->default('Fasilitas'),
                                    TextInput::make('limit')->label('Limit')->numeric()->default(6),
                                    Textarea::make('description')->label('Deskripsi')->rows(2)->columnSpanFull(),
                                ])->columns(2),
                                Builder\Block::make('gallery')->label('Gallery')->schema([
                                    TextInput::make('title')->label('Judul')->default('Galeri Kegiatan'),
                                    TextInput::make('limit')->label('Limit (dari album galeri)')->numeric()->default(12),
                                    Textarea::make('description')->label('Deskripsi')->rows(2)->columnSpanFull(),
                                    FileUpload::make('images')->label('Gambar kustom (opsional)')->multiple()->reorderable()->image()->disk('public')->directory('pages/galleries')->visibility('public')->maxSize(2048)->columnSpanFull(),
                                ])->columns(2),
                                Builder\Block::make('news')->label('News')->schema([
                                    TextInput::make('title')->label('Judul')->default('Berita dan Kegiatan'),
                                    TextInput::make('limit')->label('Limit')->numeric()->default(4),
                                ]),
                                Builder\Block::make('teachers')->label('Teachers')->schema([
                                    TextInput::make('title')->label('Judul')->default('Tenaga Pendidik'),
                                    TextInput::make('limit')->label('Limit')->numeric()->default(4),
                                ]),
                                Builder\Block::make('programs')->label('Programs')->schema([
                                    TextInput::make('title')->label('Judul')->default('Program Jurusan'),
                                    TextInput::make('limit')->label('Limit')->numeric()->default(6),
                                ]),
                                Builder\Block::make('latest_news')->label('Latest News')->schema([
                                    TextInput::make('title')->label('Judul')->default('Berita Terbaru'),
                                    TextInput::make('limit')->label('Limit')->numeric()->default(3),
                                ]),
                                Builder\Block::make('announcements')->label('Announcements')->schema([
                                    TextInput::make('title')->label('Judul')->default('Pengumuman'),
                                    TextInput::make('limit')->label('Limit')->numeric()->default(5),
                                ]),
                                Builder\Block::make('cta')->label('CTA')->schema([
                                    TextInput::make('title')->label('Judul')->required()->columnSpanFull(),
                                    Textarea::make('description')->label('Deskripsi')->rows(2)->columnSpanFull(),
                                    TextInput::make('button_label')->label('Label Tombol'),
                                    TextInput::make('button_url')->label('URL Tombol'),
                                ])->columns(2),
                                Builder\Block::make('contact')->label('Contact')->schema([
                                    Textarea::make('address')->label('Alamat')->rows(2)->columnSpanFull(),
                                    TextInput::make('phone')->label('Telepon'),
                                    TextInput::make('email')->label('Email'),
                                    TextInput::make('maps_embed_url')->label('Maps Embed URL')->columnSpanFull(),
                                ])->columns(2),
                                Builder\Block::make('faq')->label('FAQ')->schema([
                                    Textarea::make('items')->label('Items (JSON: q,a)')->rows(4)->columnSpanFull()
                                        ->formatStateUsing(fn (mixed $state): ?string => PageSections::editableText($state)),
                                ])->columns(1),
                                Builder\Block::make('custom_html')->label('Custom HTML (admin saja)')->schema([
                                    Textarea::make('html')->label('HTML tersanitasi')->rows(4)->columnSpanFull(),
                                ])->columns(1),
                            ]),
                    ])
                        ->columns(1),

                    Tab::make('Publikasi & SEO')->schema([
                        Select::make('template')->options(PageTemplate::class)->default('default')->required(),
                        Select::make('status')->options(PageStatus::class)->default('draft')->required(),
                        DateTimePicker::make('published_at'),
                        TextInput::make('seo_title')->maxLength(255),
                        Textarea::make('seo_description')->rows(2)->columnSpanFull(),
                        TextInput::make('canonical_url')->url()->columnSpanFull(),
                    ])->columns(2),
                ])
                ->columnSpanFull(),
        ]);
    }
}
