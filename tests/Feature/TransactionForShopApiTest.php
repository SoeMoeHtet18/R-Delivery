<?php

namespace Tests\Feature;

use App\Models\ShopUser;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransactionForShopApiTest extends TestCase
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
    public function test_get_transaction_for_shop(): void
    {
        $this->withoutExceptionHandling();
        $out = 'test_get_transaction_for_shop';
        var_dump($out);

        $shop_user = $this->get_authenticated_shop_user();

        $response = $this->get('/api/transactions-for-shop/'. $shop_user->id .'/get-transactions-for-shop-list');
        $response->assertStatus(200);
    }

    public function test_get_transaction_detail_for_shop(): void
    {
        $out = 'test_get_transaction_detail_for_shop';
        var_dump($out);

        $shop_user = $this->get_authenticated_shop_user();

        $response = $this->getJson('/api/get-transactions-for-shop-detail/'. $shop_user->id);
        $response->assertStatus(200);
    }
}
