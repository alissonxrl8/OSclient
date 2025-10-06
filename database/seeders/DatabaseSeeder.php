<?php

namespace Database\Seeders;

use App\Models\Loja;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // IMPORTANTE: para fazer hash da senha

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'alisson@gmail.com',
            'level' => 1,
            'password' => Hash::make('12345'),
            'id_loja'=>1
        ]);

        Loja::create([
    'nome' => 'gkcell',
    'cnpj' => '123456789',
]);
        
    }
}
