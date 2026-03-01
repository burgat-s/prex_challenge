<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (app()->environment('local')) {
            User::updateOrCreate(
                ['email' => 'test@test.com'],
                [
                    'name' => 'Usuario Test',
                    'password' => Hash::make('12345678'),
                ]
            );
        } else if (app()->environment('production')) {
            User::updateOrCreate(
                ['email' => env('ADMIN_EMAIL', 'admin@prex-challenge.com')],
                [
                    'name' => 'Admin API',
                    'password' => Hash::make(env('ADMIN_PASSWORD', 'Pr3x.Ch4ll3ng3!2026')), 
                ]
            );
        }
    }
}
