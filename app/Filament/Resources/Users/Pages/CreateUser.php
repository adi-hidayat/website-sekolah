<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    /**
     * Akun dibuat oleh admin, sehingga email langsung ditandai terverifikasi (PRD §13.5).
     */
    protected function handleRecordCreation(array $data): Model
    {
        $record = new User;
        $record->fill($data);
        $record->forceFill(['email_verified_at' => now()]);
        $record->save();

        return $record;
    }
}
