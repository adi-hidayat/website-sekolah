<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('customization.primary_color', '#176b45');
        $this->migrator->add('customization.accent_color', '#b9d9a8');
        $this->migrator->add('customization.font_body', 'Inter');
        $this->migrator->add('customization.font_heading', 'Plus Jakarta Sans');
    }
};
