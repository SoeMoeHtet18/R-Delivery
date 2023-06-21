<?php

namespace Tests\Feature;

use App\Helpers\Helper;
use App\Helpers\MyHelper;
use App\Models\City;
use App\Models\ItemType;
use App\Models\Order;
use App\Models\Shop;
use App\Models\Township;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    public function get_authenticated_user()
    {
        $admin_phone_number = config('app.admin_phone_number');
        $admin = User::select('users.*')->where('phone_number',$admin_phone_number)->first();
        $this->actingAs($admin);
        return $admin;
    }
    /**
     * A basic feature test example.
     */
    public function test_order_web(): void
    {
        $out = 'test_order_web';
        var_dump($out);
        $admin = $this->get_authenticated_user();

        $response = $this->get('/orders');
        $response->assertStatus(200);

        $dt_url = '/ajax-get-orders-data?_=1680252038384&city=&columns%5B0%5D%5Bdata%5D=DT_RowIndex&columns%5B0%5D%5Bname%5D=id&columns%5B0%5D%5Bsearchable%5D=true&columns%5B0%5D%5Borderable%5D=true&columns%5B0%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B0%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B1%5D%5Bdata%5D=total_amount&columns%5B1%5D%5Bname%5D=total_amount&columns%5B1%5D%5Bsearchable%5D=true&columns%5B1%5D%5Borderable%5D=true&columns%5B1%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B1%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B2%5D%5Bdata%5D=order_code&columns%5B2%5D%5Bname%5D=order_code&columns%5B2%5D%5Bsearchable%5D=true&columns%5B2%5D%5Borderable%5D=true&columns%5B2%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B2%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B3%5D%5Bdata%5D=shop_name&columns%5B3%5D%5Bname%5D=shop&columns%5B3%5D%5Bsearchable%5D=true&columns%5B3%5D%5Borderable%5D=true&columns%5B3%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B3%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B4%5D%5Bdata%5D=rider_name&columns%5B4%5D%5Bname%5D=rider&columns%5B4%5D%5Bsearchable%5D=true&columns%5B4%5D%5Borderable%5D=true&columns%5B4%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B4%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B5%5D%5Bdata%5D=customer_name&columns%5B5%5D%5Bname%5D=customer_name&columns%5B5%5D%5Bsearchable%5D=true&columns%5B5%5D%5Borderable%5D=true&columns%5B5%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B5%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B6%5D%5Bdata%5D=customer_phone_number&columns%5B6%5D%5Bname%5D=customer_phone_number&columns%5B6%5D%5Bsearchable%5D=true&columns%5B6%5D%5Borderable%5D=true&columns%5B6%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B6%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B7%5D%5Bdata%5D=city_name&columns%5B7%5D%5Bname%5D=city&columns%5B7%5D%5Bsearchable%5D=true&columns%5B7%5D%5Borderable%5D=true&columns%5B7%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B7%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B8%5D%5Bdata%5D=township_name&columns%5B8%5D%5Bname%5D=township&columns%5B8%5D%5Bsearchable%5D=true&columns%5B8%5D%5Borderable%5D=true&columns%5B8%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B8%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B9%5D%5Bdata%5D=quantity&columns%5B9%5D%5Bname%5D=quantity&columns%5B9%5D%5Bsearchable%5D=true&columns%5B9%5D%5Borderable%5D=true&columns%5B9%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B9%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B10%5D%5Bdata%5D=delivery_fees&columns%5B10%5D%5Bname%5D=delivery_fees&columns%5B10%5D%5Bsearchable%5D=true&columns%5B10%5D%5Borderable%5D=true&columns%5B10%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B10%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B11%5D%5Bdata%5D=markup_delivery_fees&columns%5B11%5D%5Bname%5D=markup_delivery_fees&columns%5B11%5D%5Bsearchable%5D=true&columns%5B11%5D%5Borderable%5D=true&columns%5B11%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B11%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B12%5D%5Bdata%5D=remark&columns%5B12%5D%5Bname%5D=remark&columns%5B12%5D%5Bsearchable%5D=true&columns%5B12%5D%5Borderable%5D=true&columns%5B12%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B12%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B13%5D%5Bdata%5D=status&columns%5B13%5D%5Bname%5D=status&columns%5B13%5D%5Bsearchable%5D=true&columns%5B13%5D%5Borderable%5D=true&columns%5B13%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B13%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B14%5D%5Bdata%5D=item_type&columns%5B14%5D%5Bname%5D=item_type&columns%5B14%5D%5Bsearchable%5D=true&columns%5B14%5D%5Borderable%5D=true&columns%5B14%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B14%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B15%5D%5Bdata%5D=full_address&columns%5B15%5D%5Bname%5D=full_address&columns%5B15%5D%5Bsearchable%5D=true&columns%5B15%5D%5Borderable%5D=true&columns%5B15%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B15%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B16%5D%5Bdata%5D=schedule_date&columns%5B16%5D%5Bname%5D=schedule_date&columns%5B16%5D%5Bsearchable%5D=true&columns%5B16%5D%5Borderable%5D=true&columns%5B16%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B16%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B17%5D%5Bdata%5D=type&columns%5B17%5D%5Bname%5D=type&columns%5B17%5D%5Bsearchable%5D=true&columns%5B17%5D%5Borderable%5D=true&columns%5B17%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B17%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B18%5D%5Bdata%5D=collection_method&columns%5B18%5D%5Bname%5D=collection_method&columns%5B18%5D%5Bsearchable%5D=true&columns%5B18%5D%5Borderable%5D=true&columns%5B18%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B18%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B19%5D%5Bdata%5D=last_updated_by_name&columns%5B19%5D%5Bname%5D=last_updated_by&columns%5B19%5D%5Bsearchable%5D=true&columns%5B19%5D%5Borderable%5D=true&columns%5B19%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B19%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B20%5D%5Bdata%5D=action&columns%5B20%5D%5Bname%5D=action&columns%5B20%5D%5Bsearchable%5D=false&columns%5B20%5D%5Borderable%5D=false&columns%5B20%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B20%5D%5Bsearch%5D%5Bregex%5D=false&draw=1&length=10&order%5B0%5D%5Bcolumn%5D=0&order%5B0%5D%5Bdir%5D=asc&rider=&search=&shop=&start=0&status=&township=';
        $response = $this->getJson($dt_url);
        $response->assertStatus(200)->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
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
                    "last_updated_by_name",
                    "city_name",
                    'action',
                    'DT_RowIndex'
                ]
            ]
        ]);
    }

    public function  test_get_create_order_web(): void
    {
        $out = " test_get_create_order_web";
        var_dump($out);
        $admin = $this->get_authenticated_user();
        
        $response = $this->get('/orders/create');
        $response->assertStatus(200);
    }

    public function test_store_order(): void
    {
        $out = 'test_store_order';
        var_dump($out);
        $admin = $this->get_authenticated_user();

        $types = ['express','standard','door-to-door'];
        $rand_type = $types[array_rand($types)];

        $methods = ['drop-off','pick-up'];
        $rand_method = $methods[array_rand($methods)];

        $shop_id = Shop::all()->random()->id;

        $response = $this->post('/orders', [
            "order_code" => Helper::nomenclature('orders', 'OD', 'order_code', $shop_id),
            "shop_id" => $shop_id,
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
            "schedule_date" => Carbon::now(),
            "type" => $rand_type,
            "collection_method" => $rand_method
        ]);
        $response->assertStatus(302)
            ->assertRedirect('/orders');
    }

    public function test_get_customer_by_phone_number(): void
    {
        $out = 'test_get_customer_by_phone_number';
        var_dump($out);
        $admin = $this->get_authenticated_user();
        
        $customer_phone_number = Order::all()->random()->customer_phone_number;
        $response = $this->postJson('api/get-data-by-customer-phone', [
            'phone_number' => $customer_phone_number
        ]);
        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
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
            ],
            'status',
            'message'
        ]);
    }

    public function test_get_townships_by_city(): void 
    {
        $out = 'test_get_townships_by_city';
        var_dump($out);
        $admin = $this->get_authenticated_user();
        
        $response = $this->postJson('/api/townships-get-by-city');
        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'created_at',
                    'updated_at',
                    'city_id',
                    'deleted_at'
                ]
            ]
        ]);
    }

    public function test_order_detail_web(): void
    {
        $out = 'test_order_detail_web';
        var_dump($out);
        $admin = $this->get_authenticated_user();

        $order_id = Order::all()->random()->id;

        $response = $this->get('/orders/' . $order_id);
        $response->assertStatus(200);
    }

    public function test_order_edit_web(): void
    {
        $out = 'test_order_edit_web';
        var_dump($out);
        $admin = $this->get_authenticated_user();

        $order_id = Order::all()->random()->id;

        $response = $this->get('/orders/' . $order_id . '/edit');
        $response->assertStatus(200);
    }

    public function test_update_order(): void
    {
        $out = 'test_update_order';
        var_dump($out);
        $admin = $this->get_authenticated_user();

        $types = ['express','standard','door-to-door'];
        $rand_type = $types[array_rand($types)];

        $methods = ['drop-off','pick-up'];
        $rand_method = $methods[array_rand($methods)];

        $order = Order::all()->random();
        $township =  Township::whereHas('riders')->get()->random();
        $rider = $township->riders->random();

        $status = ['success','pending','delay','cancel'];
        $rand_status = $status[array_rand($status)];

        $file = UploadedFile::fake()->create('test-image.jpg', 200, 'image/jpeg');

        $response = $this->put('/orders/' . $order->id , [
            "order_code" => $order->order_code,
            "shop_id" => Shop::all()->random()->id,
            "customer_phone_number" => $this->faker->phoneNumber,
            "customer_name" => $this->faker->name,
            "city_id" => City::all()->random()->id,
            "township_id" => $township->id,
            'rider_id' => $rider->id,
            "quantity" => $this->faker->randomNumber(1,10),
            "total_amount" => $this->faker->randomDigit,
            "delivery_fees" => $this->faker->randomNumber(1,1000),
            "markup_delivery_fees" => $this->faker->randomNumber(1,1000),
            "remark" => $this->faker->text,
            "status" => $rand_status,
            "item_type" => ItemType::all()->random()->name,
            "full_address" => $this->faker->address,
            "schedule_date" => Carbon::now(),
            "type" => $rand_type,
            "collection_method" => $rand_method,
            'proof_of_payment' => $file
        ]);
        $response->assertStatus(302)
            ->assertRedirect('/orders');
    }

    public function test_get_riders_by_township(): void
    {
        $out = 'test_get_riders_by_township';
        var_dump($out);
        $admin = $this->get_authenticated_user();

        $response = $this->postJson('/api/riders-get-by-township', [
            'township_id' => Township::all()->random()->id
        ]);
        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => [
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
                    "refresh_token",
                    "deleted_at",
                    "pivot" => [
                        "township_id",
                        "rider_id"
                    ]
                ]
            ],
            'message',
            'status'
        ]);
    }

    public function test_delete_order(): void
    {
        $out = 'test_delete_order';
        var_dump($out);
        $admin = $this->get_authenticated_user();

        $order_id = Order::all()->random()->id;

        $response = $this->delete('/orders/' . $order_id);
        $response->assertStatus(302)
            ->assertRedirect('/orders');
    }

    public function test_assign_rider_web(): void 
    {
        $out = 'test_assign_rider_web';
        var_dump($out);
        $admin = $this->get_authenticated_user();
        
        $order_id = Order::all()->random()->id;

        $response = $this->get('/orders/' . $order_id . '/assign-rider');
        $response->assertStatus(200);
    }

    public function test_assign_rider(): void 
    {
        $out = 'test_assign_rider';
        var_dump($out);
        $admin = $this->get_authenticated_user();
        
        $order = Order::all()->random();
        $township =  $order->township;
        $rider = $township->riders->random();

        $response = $this->post('/orders/' . $order->id . '/assign-rider', [
            'rider_id' => $rider->id
        ]);
        $response->assertStatus(302)
            ->assertRedirect('/orders');
    }
}
