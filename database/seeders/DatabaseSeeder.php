<?php

namespace Database\Seeders;

use App\Models\RepairRequest;
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
        User::create([
            'name' => 'Диспетчер Леонов В.И.',
            'email' => 'dispatcher1@example.com',
            'password' => Hash::make('password'),
            'role' => 'dispatcher',
        ]);

        User::create([
            'name' => 'Диспетчер Бессонов Е.Э.',
            'email' => 'dispatcher2@example.com',
            'password' => Hash::make('password'),
            'role' => 'dispatcher',
        ]);

        User::create([
            'name' => 'Мастер Сыроева В.О.',
            'email' => 'master1@example.com',
            'password' => Hash::make('password'),
            'role' => 'master',
        ]);

        User::create([
            'name' => 'Мастер Никитин Н.М.',
            'email' => 'master2@example.com',
            'password' => Hash::make('password'),
            'role' => 'master',
        ]);

        User::create([
            'name' => 'Мастер Лаврентьев Б.А.',
            'email' => 'master3@example.com',
            'password' => Hash::make('password'),
            'role' => 'master',
        ]);

        RepairRequest::factory(11)->create();
    }
}
