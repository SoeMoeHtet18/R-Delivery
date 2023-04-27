<?php

namespace Tests\Feature;

use App\Helpers\MyHelper;
use App\Models\City;
use App\Models\ItemType;
use App\Models\Order;
use App\Models\ShopUser;
use App\Models\Township;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ShopUserApiTest extends TestCase
{
    use WithFaker, HasFactory;

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

    public function test_create_shop_user(): void
    {
        // Start a new database transaction
        DB::beginTransaction();
        
        try {
            $this->withoutExceptionHandling();
            $out = 'test_create_shop_user';
            var_dump($out);

            $phone_number = $this->faker->phoneNumber;
            $password = $this->faker->password;

            $response = $this->postJson('/api/shop-user/create', [
                'name' => $this->faker->name,
                'phone_number' => $phone_number,
                'email' => $this->faker->email,
                'password' => $password
            ]);
            $response->assertStatus(200);

            $response = $this->postJson('/api/shop_user_login', [
                'phone_number' => $phone_number,
                'password' => $password
            ]);
            $response->assertStatus(200);

            // Commit the transaction
            DB::commit();

        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurred
            DB::rollback();

            throw $e;
        }
    }


    public function test_update_shop_user(): void
    {
        $out = 'test_update_shop_user';
        var_dump($out);

        $shop_user = $this->get_authenticated_shop_user();
        DB::beginTransaction();
        $response = $this->postJson('/api/shop-user/update', [
            'name' => $this->faker->name,
            'phone_number' => $this->faker->phoneNumber,
            'email' => $this->faker->email,
            'password' => $this->faker->password
        ]);
        $response->assertStatus(200);
    }

    public function test_shop_user_detail(): void
    {
        $out = 'test_shop_user_detail';
        var_dump($out);

        $shop_user = $this->get_authenticated_shop_user();
        $shop_user_id = ShopUser::all()->random()->id;

        $response = $this->getJson('/api/shop-user');
        $response->assertStatus(200);
    }

    public function test_get_order_list_by_shop_owner(): void
    {
        $out = 'test_get_order_list_by_shop_owner';
        var_dump($out);

        $shop_user = $this->get_authenticated_shop_user();

        $response = $this->getJson('/api/shop-user/get-order-list');
        $response->assertStatus(200);
    }

    public function test_create_order(): void
    {
        $out = 'test_create_order';
        var_dump($out);

        $shop_user = $this->get_authenticated_shop_user();

        $types = ['express','standard','door-to-door'];
        $rand_type = $types[array_rand($types)];

        $methods = ['drop-off','pick-up'];
        $rand_method = $methods[array_rand($methods)];
        DB::beginTransaction();
        $response = $this->postJson('/api/shop-user/create-order-list', [
            "order_code" => MyHelper::nomenclature(['table_name'=>'orders','prefix'=>'OD','column_name'=>'order_code']),
            "customer_phone_number" => $this->faker->phoneNumber,
            "customer_name" => $this->faker->name,
            "city_id" => City::all()->random()->id,
            "township_id" => Township::all()->random()->id,
            "quantity" => $this->faker->randomNumber(1,10),
            "total_amount" => $this->faker->randomDigit,
            "delivery_fees" => $this->faker->randomNumber(1,1000),
            "markup_delivery_fees" => $this->faker->randomNumber(1,1000),
            "remark" => $this->faker->text,
            "item_type" => ItemType::all()->random()->name,
            "full_address" => $this->faker->address,
            "schedule_date" => Carbon::now()->format('Y/m/d'),
            "type" => $rand_type,
            "collection_method" => $rand_method
        ]);
        $response->assertStatus(200);
    }

    public function test_delete_shop_user(): void
    {
        $this->withoutExceptionHandling();
        $out = 'test_delete_shop_user';
        var_dump($out);

        $shop_user = $this->get_authenticated_shop_user();
        DB::beginTransaction();
        $response = $this->postJson('/api/shop-user/delete');
        $response->assertStatus(200);
    }

    public function test_change_order_status(): void
    {
        $this->withoutExceptionHandling();
        $out = 'test_change_order_status';
        var_dump($out);

        $shop_user = $this->get_authenticated_shop_user();
        $shop_id = $shop_user->shop_id;
        DB::beginTransaction();
        $response = $this->postJson('/api/shop_user/change-order-status', [
            'status' => 'cancel',
            'order_id' => Order::where('shop_id', $shop_id)->first()->id,
        ]);
        $response->assertStatus(200);
    }
}
