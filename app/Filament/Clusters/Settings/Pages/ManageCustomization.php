<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings\SettingsCluster;
use App\Settings\CustomizationSettings;
use App\Support\SitePresentation;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Support\Icons\Heroicon;

class ManageCustomization extends Page
{
    protected static ?string $cluster = SettingsCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSwatch;

    protected static ?string $navigationLabel = 'Kustomisasi';

    protected static ?string $title = 'Kustomisasi';

    protected static ?string $slug = 'kustomisasi';

    protected static ?int $navigationSort = 2;

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $settings = app(CustomizationSettings::class);

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
                Section::make('Warna Aksen')
                    ->description('Ubah warna utama dan aksen yang dipakai di seluruh website publik.')
                    ->icon(Heroicon::OutlinedSwatch)
                    ->schema([
                        ColorPicker::make('primary_color')
                            ->label('Warna Primer')
                            ->required()
                            ->hex(),
                        ColorPicker::make('accent_color')
                            ->label('Warna Aksen')
                            ->required()
                            ->hex(),
                    ])
                    ->columns(1),
                Section::make('Tipografi')
                    ->description('Pilih jenis font untuk teks isi dan judul.')
                    ->icon(Heroicon::OutlinedBars3BottomLeft)
                    ->schema([
                        Select::make('font_body')
                            ->label('Font Isi')
                            ->options(CustomizationSettings::fontOptions())
                            ->required()
                            ->searchable(),
                        Select::make('font_heading')
                            ->label('Font Judul')
                            ->options(CustomizationSettings::fontOptions())
                            ->required()
                            ->searchable(),
                    ])
                    ->columns(1),
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

        $settings = app(CustomizationSettings::class);
        $settings->fill($data);
        $settings->save();

        SitePresentation::clearCaches();

        Notification::make()
            ->title('Kustomisasi disimpan')
            ->success()
            ->send();
    }
}
