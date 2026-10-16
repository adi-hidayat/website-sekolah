<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public const DEFAULT_PASSWORD = '12345678';

    /**
     * Akun admin dan akun contoh untuk pengelolaan konten.
     */
    public function run(): void
    {
        $accounts = [
            [
                'email' => 'admin@example.com',
                'name' => 'Administrator Sekolah',
                'password' => self::DEFAULT_PASSWORD,
            ],
            [
                'email' => 'admin@smanusantara.sch.id',
                'name' => 'Administrator',
                'password' => 'password',
            ],
            [
                'email' => 'editor@example.com',
                'name' => 'Editor Konten',
                'password' => self::DEFAULT_PASSWORD,
            ],
        ];

        foreach ($accounts as $account) {
            User::updateOrCreate(
                ['email' => $account['email']],
                [
                    'name' => $account['name'],
                    'password' => $account['password'],
                    'email_verified_at' => now(),
                ],
            );
        }
    }
}
