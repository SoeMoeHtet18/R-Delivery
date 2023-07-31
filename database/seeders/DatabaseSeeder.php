<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Rider;
use App\Models\ShopUser;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        User::create([
            'name' => 'Admin',
            'phone_number' => '09123456789',
            'password' => bcrypt('admin123')
        ]);
        Rider::create([
            'name' => 'Rider',
            'phone_number' => '09123456789',
            'password' => bcrypt('rider123'),
            'salary_type'=> 'daily'
        ]);
        ShopUser::create([
            'name' => 'Shop User',
            'phone_number' => '09123456789',
            'password' => bcrypt('shopuser123')
        ]);
    }
}
