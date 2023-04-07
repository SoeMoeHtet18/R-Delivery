<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Rider;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RiderApiTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    public function get_authenticated_rider()
    {
        $rider_phone_number = config('app.admin_phone_number');
        $rider = Rider::where('phone_number',$rider_phone_number)->first();
        $this->actingAs($rider, 'rider-api');
        return $rider;
    }

    public function test_create_rider()
    {
        $out = 'test_create_rider';
        var_dump($out);

        $response = $this->postJson('/api/rider/create', [
            'name' => $this->faker->name,
            'phone_number' => $this->faker->phoneNumber,
            'email' => $this->faker->email,
            'password' => $this->faker->password
        ]);
        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                "id",
                "name",
                "phone_number",
                "email",
                "password",
                "device_id",
                "created_at",
                "updated_at",
                "token",
                'refresh_token',
            ],
            'message',
            'status'
        ]);
    }
    /**
     * A basic feature test example.
     */
    public function test_rider_login(): void
    {
        $this->withoutExceptionHandling();
        $out = 'test_rider_login';
        var_dump($out);
        
        $response = $this->postJson('/api/rider-login', [
            'phone_number' => '09123456789',
            'password' => 'rider123'
        ]);
        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                "id",
                "name",
                "phone_number",
                "email",
                "email_verified_at",
                "password",
                "device_id",
                "remember_token",
                "created_at",
                "updated_at",
                "token",
                'refresh_token',
                'deleted_at'
            ],
            'message',
            'status'
        ]);
    }

    public function test_rider_detail()
    {
        $out = 'test_rider_detail';
        var_dump($out);

        $rider = $this->get_authenticated_rider();

        $response = $this->getJson('/api/rider');
        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                "id",
                "name",
                "phone_number",
                "email",
                "email_verified_at",
                "password",
                "device_id",
                "remember_token",
                "created_at",
                "updated_at",
                "token",
                'refresh_token',
                'deleted_at'
            ],
            'message',
            'status'
        ]);
    }

    public function test_get_pending_order_list()
    {
        $this->withoutExceptionHandling();
        $out = 'test_get_pending_order_list';
        var_dump($out);

        $rider = $this->get_authenticated_rider();

        $response = $this->postJson('/api/rider/get-order-list', [
            'status' => 'pending'
        ]);
        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => [
                    "id",
                    "order_code",
                    "customer_name",
                    "customer_phone_number",
                    "township_id",
                    "rider_id",
                    "shop_id",
                    "quantity",
                    "total_amount",
                    "delivery_fees",
                    "markup_delivery_fees",
                    "remark",
                    "status",
                    "item_type",
                    "full_address",
                    "schedule_date",
                    "type",
                    "collection_method",
                    "proof_of_payment",
                    "last_updated_by",
                    "deleted_at",
                    "created_at",
                    "updated_at",
                    "city_id",
                    "township_name",
                    "shop_name",
                    "rider_name",
                    "last_updated_by_name"
                ],
            ],
            'message',
            'status'
        ]);
    }

    public function test_get_shop_list()
    {
        $out = 'test_get_shop_list';
        var_dump($out);
        $rider = $this->get_authenticated_rider();

        $response = $this->getJson('/api/rider/get-shop-list');
        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'address',
                    'phone_number',
                    'created_at',
                    'updated_at',
                    'deleted_at'
                ]
            ],
            'message',
            'status'
        ]);
    }

    public function test_update_rider()
    {
        $out = 'test_update_rider';
        var_dump($out);
        $rider = $this->get_authenticated_rider();

        $response = $this->postJson('/api/rider', [
            'name' => $this->faker->name,
            'phone_number' => $this->faker->phoneNumber,
            'email' => $this->faker->email,
            'password' => $this->faker->password
        ]);
        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                "id",
                "name",
                "phone_number",
                "email",
                "email_verified_at",
                "password",
                "device_id",
                "remember_token",
                "created_at",
                "updated_at",
                "token",
                'refresh_token',
                'deleted_at'
            ],
            'message',
            'status'
        ]);
    }

    public function test_change_status()
    {
        $out = 'test_change_status';
        var_dump($out);
        $rider = $this->get_authenticated_rider();

        $status = [ 'success', 'delay', 'cancel'];
        $rand_status = $status[array_rand($status)];

        $response = $this->postJson('/api/rider/change-order-status', [
            'order_id' => Order::all()->random()->id,
            'status' => $rand_status
        ]);
        $response->assertStatus(200);
    }
}
