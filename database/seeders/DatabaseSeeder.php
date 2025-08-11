<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

       User::create([
            'name' => 'Admin',
            'password' => Hash::make('222222'),
            'phone' => '0882948977',
            'role' => 'admin'
        ]);
       User::create([
            'name' => 'Pemilik',
            'password' => Hash::make('222222'),
            'phone' => '0882944277',
            'role' => 'pemilik'
        ]);
      
        
    }
}
