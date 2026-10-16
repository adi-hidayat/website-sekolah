<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings\SettingsCluster;
use App\Models\SchoolSetting;
use App\Settings\GeneralSettings;
use App\Support\SitePresentation;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Support\Icons\Heroicon;

class ManageGeneral extends Page
{
    protected static ?string $cluster = SettingsCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAlt;

    protected static ?string $navigationLabel = 'General';

    protected static ?string $title = 'General';

    protected static ?string $slug = 'general';

    protected static ?int $navigationSort = 1;

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $this->fillForm();
    }

    protected function fillForm(): void
    {
        $settings = app(GeneralSettings::class);

        $this->form->fill($settings->toArray());
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema
            ->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('general')
                    ->tabs([
                        Tab::make('Informasi Website')
                            ->icon(Heroicon::OutlinedGlobeAlt)
                            ->schema([
                                TextInput::make('site_name')
                                    ->label('Nama Website')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('site_tagline')
                                    ->label('Tagline')
                                    ->maxLength(255)
                                    ->columnSpanFull(),
                                FileUpload::make('logo')
                                    ->label('Logo')
                                    ->image()
                                    ->disk('public')
                                    ->directory('settings')
                                    ->visibility('public')
                                    ->maxSize(2048),
                                FileUpload::make('favicon')
                                    ->label('Favicon')
                                    ->image()
                                    ->disk('public')
                                    ->directory('settings')
                                    ->visibility('public')
                                    ->maxSize(1024)
                                    ->helperText('Ikon kecil untuk tab browser. Disarankan PNG 32×32 atau SVG.'),
                                TextInput::make('seo_title')
                                    ->label('Judul SEO')
                                    ->maxLength(255)
                                    ->helperText('Judul yang tampil di hasil pencarian. Kosongkan untuk memakai nama website.')
                                    ->columnSpanFull(),
                                Textarea::make('seo_description')
                                    ->label('Deskripsi SEO')
                                    ->rows(3)
                                    ->maxLength(500)
                                    ->helperText('Ringkasan 150–160 karakter untuk mesin pencari.')
                                    ->columnSpanFull(),
                                TextInput::make('seo_keywords')
                                    ->label('Kata Kunci SEO')
                                    ->maxLength(500)
                                    ->helperText('Pisahkan dengan koma.')
                                    ->columnSpanFull(),
                                FileUpload::make('og_image')
                                    ->label('Gambar Open Graph')
                                    ->image()
                                    ->disk('public')
                                    ->directory('settings')
                                    ->visibility('public')
                                    ->maxSize(2048)
                                    ->helperText('Gambar pratinjau saat tautan dibagikan ke media sosial.')
                                    ->columnSpanFull(),
                            ])
                            ->columns(1),
                        Tab::make('Informasi Organisasi')
                            ->icon(Heroicon::OutlinedBuildingOffice2)
                            ->schema([
                                TextInput::make('school_name')
                                    ->label('Nama Sekolah')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('short_name')
                                    ->label('Nama Singkat')
                                    ->maxLength(255),
                                TextInput::make('npsn')
                                    ->label('NPSN')
                                    ->maxLength(50),
                                TextInput::make('accreditation')
                                    ->label('Akreditasi')
                                    ->maxLength(10),
                                Textarea::make('address')
                                    ->label('Alamat')
                                    ->rows(2)
                                    ->columnSpanFull(),
                                TextInput::make('phone')
                                    ->label('Telepon')
                                    ->tel()
                                    ->maxLength(50),
                                TextInput::make('whatsapp')
                                    ->label('Nomor WhatsApp')
                                    ->tel()
                                    ->maxLength(50)
                                    ->helperText('Format: 62812xxxxxxx. Dipakai tombol Hubungi Kami dan ikon media sosial.'),
                                TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->maxLength(255),
                                TextInput::make('website')
                                    ->label('Website')
                                    ->url()
                                    ->maxLength(255),
                                TextInput::make('principal_name')
                                    ->label('Nama Kepala Sekolah')
                                    ->maxLength(255),
                                FileUpload::make('principal_photo')
                                    ->label('Foto Kepala Sekolah')
                                    ->image()
                                    ->disk('public')
                                    ->directory('settings')
                                    ->visibility('public')
                                    ->maxSize(2048),
                                RichEditor::make('principal_welcome')
                                    ->label('Sambutan Kepala Sekolah')
                                    ->columnSpanFull(),
                                Textarea::make('vision')
                                    ->label('Visi')
                                    ->rows(3)
                                    ->columnSpanFull(),
                                Textarea::make('mission')
                                    ->label('Misi')
                                    ->rows(5)
                                    ->columnSpanFull(),
                                RichEditor::make('history')
                                    ->label('Sejarah')
                                    ->columnSpanFull(),
                                TextInput::make('maps_embed_url')
                                    ->label('URL Embed Peta')
                                    ->url()
                                    ->columnSpanFull(),
                                KeyValue::make('social_links')
                                    ->label('Media Sosial')
                                    ->keyLabel('Platform')
                                    ->valueLabel('URL')
                                    ->columnSpanFull(),
                                Repeater::make('statistics')
                                    ->label('Statistik')
                                    ->schema([
                                        TextInput::make('label')
                                            ->label('Label')
                                            ->required(),
                                        TextInput::make('value')
                                            ->label('Nilai')
                                            ->required(),
                                    ])
                                    ->columns(1)
                                    ->columnSpanFull(),
                            ])
                            ->columns(1),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    /**
     * @return array<Action>
     */
    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan')
                ->submit('save')
                ->keyBindings(['mod+s']),
        ];
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([EmbeddedSchema::make('form')])
                    ->id('form')
                    ->livewireSubmitHandler('save')
                    ->footer([
                        Actions::make($this->getFormActions())
                            ->alignment(Alignment::Start),
                    ]),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $settings = app(GeneralSettings::class);
        $settings->fill($data);
        $settings->save();

        $this->syncLegacySchoolSetting($data);

        SitePresentation::clearCaches();

        Notification::make()
            ->title('Pengaturan umum disimpan')
            ->success()
            ->send();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function syncLegacySchoolSetting(array $data): void
    {
        try {
            $record = SchoolSetting::query()->first() ?? new SchoolSetting;

            $record->fill(collect($data)->only((new SchoolSetting)->getFillable())->all());
            $record->save();
        } catch (\Throwable) {
            // Tabel legacy boleh tidak ada; settings tetap tersimpan.
        }
    }
}
