<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public const DEFAULT_PASSWORD = '12345678';

    /**
     * Akun admin dan akun contoh untuk setiap peran (PRD §13).
     */
    public function run(): void
    {
        $accounts = [
            [
                'email' => 'admin@example.com',
                'name' => 'Administrator Sekolah',
                'password' => self::DEFAULT_PASSWORD,
                'role' => 'super_admin',
            ],
            [
                'email' => 'admin@smanusantara.sch.id',
                'name' => 'Administrator',
                'password' => 'password',
                'role' => 'super_admin',
            ],
            [
                'email' => 'administrator@example.com',
                'name' => 'Wakil Kepala Sekolah',
                'password' => self::DEFAULT_PASSWORD,
                'role' => 'administrator',
            ],
            [
                'email' => 'editor@example.com',
                'name' => 'Editor Konten',
                'password' => self::DEFAULT_PASSWORD,
                'role' => 'editor',
            ],
            [
                'email' => 'kontributor@example.com',
                'name' => 'Kontributor Berita',
                'password' => self::DEFAULT_PASSWORD,
                'role' => 'kontributor',
            ],
            [
                'email' => 'viewer@example.com',
                'name' => 'Pengamat Data',
                'password' => self::DEFAULT_PASSWORD,
                'role' => 'viewer',
            ],
        ];

        foreach ($accounts as $account) {
            $user = User::updateOrCreate(
                ['email' => $account['email']],
                [
                    'name' => $account['name'],
                    'password' => $account['password'],
                    'email_verified_at' => now(),
                ],
            );

            $user->syncRoles([$account['role']]);
        }
    }
}
