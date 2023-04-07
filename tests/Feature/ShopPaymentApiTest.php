<?php

namespace Tests\Feature;

use App\Models\ShopPayment;
use App\Models\ShopUser;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ShopPaymentApiTest extends TestCase
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
    public function test_create_shop_payment(): void
    {
        $out = 'test_create_shop_payment';
        var_dump($out);

        $shop_user = $this->get_authenticated_shop_user();
      
        $type = ['delivery_payment', 'remaining_payment'];
        $rand_type = $type[array_rand($type)];

        $file = UploadedFile::fake()->create('test-image.jpg', 200, 'image/jpeg');

        $response = $this->postJson('/api/shop-user/create-shop-payment', [
            'amount' => $this->faker->randomDigit,
            'type' => $rand_type,
            'image' =>  $file
        ]);
        $response->assertStatus(200);
    }

    public function test_get_shop_payment_list(): void
    {
        $out = 'test_get_shop_payment_list';
        var_dump($out);

        $shop_user = $this->get_authenticated_shop_user();

        $response = $this->getJson('/api/shop_user/get-shop_payment-list');
        $response->assertStatus(200);
    }

    public function test_get_shop_payment_detail(): void
    {
        $out = 'test_get_shop_payment_detail';
        var_dump($out);

        $shop_user = $this->get_authenticated_shop_user();
        $shop_id = $shop_user->shop_id;
        $shop_payment = ShopPayment::where('shop_id', $shop_id)->get()->random();
        $response = $this->getJson('/api/get-shop_payment-detail/' . $shop_payment->id);
        $response->assertStatus(200);
    }
}
