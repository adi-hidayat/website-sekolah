<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\User;

class UserObserver
{
    public function created(User $user): void
    {
        ActivityLog::record('user.created', $user, "Pengguna '{$user->email}' dibuat.");
    }

    public function updated(User $user): void
    {
        // Abaikan perubahan teknis seperti remember_token agar log tetap bermakna (PRD §13.5).
        if (! $user->wasChanged(['name', 'email', 'email_verified_at', 'password'])) {
            return;
        }

        ActivityLog::record('user.updated', $user, "Pengguna '{$user->email}' diperbarui.");
    }

    public function deleted(User $user): void
    {
        ActivityLog::record('user.deleted', $user, "Pengguna '{$user->email}' dihapus.");
    }
}
