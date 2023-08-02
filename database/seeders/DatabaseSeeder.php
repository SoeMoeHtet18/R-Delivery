<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Branch;
use App\Models\City;
use App\Models\ItemType;
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
        City::create([
            'name' => 'Yangon'
        ]);
        Branch::create([
            'name' => 'Main Branch',
            'city_id' => '1',
            'address' => 'Yangon',
            'phone_number' => '09123456789'
        ]);
        User::create([
            'name' => 'Admin',
            'phone_number' => '09123456789',
            'password' => bcrypt('admin123'),
            'branch_id' => 1
        ]);
        Rider::create([
            'name' => 'Rider',
            'phone_number' => '09123456789',
            'password' => bcrypt('rider123'),
            'salary_type' => 'daily',
            'branch_id' => 1
        ]);
        ShopUser::create([
            'name' => 'Shop User',
            'phone_number' => '09123456789',
            'password' => bcrypt('shopuser123'),
            'branch_id' => 1
        ]);
        ItemType::create([
            'name' => 'Clothing',
        ], [
            'name' => 'Document',
        ], [
            'name' => 'Others',
        ]);
    }
}
