<?php

namespace Tests\Feature;

use App\Models\Shop;
use App\Models\ShopUser;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShopApiTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    public function get_authenticated_shop_user()
    {
        $shop_user_phone_number = config('app.admin_phone_number');
        $shop_user = ShopUser::where('phone_number', $shop_user_phone_number)->first();
        $this->actingAs($shop_user,'shop-user-api');
        return $shop_user;
    }
    /**
     * A basic feature test example.
     */
    public function test_create_shop(): void
    {
        $out = 'test_create_shop';
        var_dump($out);
        
        $response = $this->postJson('/api/create-shop', [
            'name' => $this->faker->name,
            'phone_number' => $this->faker->phoneNumber,
            'address' => $this->faker->address
        ]);
        $response->assertStatus(200);
    }

    public function test_update_shop(): void
    {
        $out = 'test_update_shop';
        var_dump($out);

        $shop_user = $this->get_authenticated_shop_user();

        $response = $this->postJson('/api/update-shop', [
            'name' => $this->faker->name,
            'phone_number' => $this->faker->phoneNumber,
            'address' => $this->faker->address
        ]);
        $response->assertStatus(200);
    }
    
    public function test_get_shop_detail(): void
    {
        $out = 'test_get_shop_detail';
        var_dump($out);
        
        $shop_user = $this->get_authenticated_shop_user();
        $shop_id = $shop_user->shop_id;

        $response = $this->getJson('/api/get-shop-info');
        $response->assertStatus(200);
    }

    public function test_get_all_shops(): void
    {
        $out = 'test_get_all_shops';
        var_dump($out);

        $response = $this->getJson('/api/get-shop-list');
        $response->assertStatus(200);
    }

    public function test_delete_shop(): void
    {
        $out = 'test_delete_shop';
        var_dump($out);

        $shop_user = $this->get_authenticated_shop_user();
        $shop_id = $shop_user->shop_id;

        $response = $this->postJson('/api/delete-shop', [
            'shop_id' => $shop_id
        ]);
        $response->assertStatus(200);
    }
}
