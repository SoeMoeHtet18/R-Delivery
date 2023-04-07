<?php

namespace Tests\Feature;

use App\Models\Rider;
use App\Models\Township;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RiderTest extends TestCase
{
    use DatabaseTransactions, WithFaker;
    /**
     * A basic feature test example.
     */
    public function get_authenticated_user()
    {
        $admin_phone_number = config('app.admin_phone_number');
        $admin = User::select('users.*')->where('phone_number',$admin_phone_number)->first();
        $this->actingAs($admin);
        return $admin;
    }

    public function test_rider_web(): void
    {   
        $out = "test_rider_web";
        var_dump($out);
        $admin = $this->get_authenticated_user();
        $response = $this->get('/riders');
        $response->assertStatus(200);

        $dt_url = "/ajax-get-riders-data?_=1680254714148&columns%5B0%5D%5Bdata%5D=DT_RowIndex&columns%5B0%5D%5Bname%5D=id&columns%5B0%5D%5Bsearchable%5D=true&columns%5B0%5D%5Borderable%5D=true&columns%5B0%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B0%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B1%5D%5Bdata%5D=name&columns%5B1%5D%5Bname%5D=name&columns%5B1%5D%5Bsearchable%5D=true&columns%5B1%5D%5Borderable%5D=true&columns%5B1%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B1%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B2%5D%5Bdata%5D=phone_number&columns%5B2%5D%5Bname%5D=phone_number&columns%5B2%5D%5Bsearchable%5D=true&columns%5B2%5D%5Borderable%5D=true&columns%5B2%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B2%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B3%5D%5Bdata%5D=email&columns%5B3%5D%5Bname%5D=email&columns%5B3%5D%5Bsearchable%5D=true&columns%5B3%5D%5Borderable%5D=true&columns%5B3%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B3%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B4%5D%5Bdata%5D=action&columns%5B4%5D%5Bname%5D=action&columns%5B4%5D%5Bsearchable%5D=false&columns%5B4%5D%5Borderable%5D=false&columns%5B4%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B4%5D%5Bsearch%5D%5Bregex%5D=false&draw=1&length=10&order%5B0%5D%5Bcolumn%5D=0&order%5B0%5D%5Bdir%5D=asc&search=&start=0";
        $response = $this->getJson($dt_url);
        $response->assertStatus(200)->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'phone_number',
                    'email',
                    'email_verified_at',
                    'password',
                    'device_id',
                    'remember_token',
                    'created_at',
                    'updated_at',
                    'token',
                    'refresh_token',
                    'deleted_at',
                    'action',
                    'DT_RowIndex'
                ]
            ]
        ]);
    }

    public function test_get_create_rider_web(): void
    {
        $out = "test_get_create_rider_web";
        var_dump($out);
        $admin = $this->get_authenticated_user();
        
        $response = $this->get('/riders/create');
        $response->assertStatus(200);
    }

    public function test_store_rider(): void
    {
        $out = "test_store_create_rider";
        var_dump($out);
        $admin = $this->get_authenticated_user();

        $password = $this->faker->password(8,20);
        $township_id = Township::all()->random()->id;
        $response = $this->post('/riders', [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'phone_number' => $this->faker->phoneNumber,
            'password' => $password,
            'password_confirmation' =>$password,
            'township_id' => [
                $township_id
            ]
        ]);
        $response->assertStatus(302)
            ->assertRedirect('/riders');
    }

    public function test_rider_detail_web(): void 
    {
        $out = "test_rider_detail_web";
        var_dump($out);
        $admin = $this->get_authenticated_user();

        $rider_id = Rider::all()->random()->id;
        $response = $this->get('/riders/' . $rider_id);
        $response->assertStatus(200);

        $order_history_dt_url = "/riders/get-order-history-by-rider-id/". $rider_id ."?_=1680255786488&columns%5B0%5D%5Bdata%5D=DT_RowIndex&columns%5B0%5D%5Bname%5D=id&columns%5B0%5D%5Bsearchable%5D=true&columns%5B0%5D%5Borderable%5D=true&columns%5B0%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B0%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B1%5D%5Bdata%5D=order_code&columns%5B1%5D%5Bname%5D=order_code&columns%5B1%5D%5Bsearchable%5D=true&columns%5B1%5D%5Borderable%5D=true&columns%5B1%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B1%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B2%5D%5Bdata%5D=customer_name&columns%5B2%5D%5Bname%5D=customer_name&columns%5B2%5D%5Bsearchable%5D=true&columns%5B2%5D%5Borderable%5D=true&columns%5B2%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B2%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B3%5D%5Bdata%5D=customer_phone_number&columns%5B3%5D%5Bname%5D=customer_phone_number&columns%5B3%5D%5Bsearchable%5D=true&columns%5B3%5D%5Borderable%5D=true&columns%5B3%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B3%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B4%5D%5Bdata%5D=township_name&columns%5B4%5D%5Bname%5D=township&columns%5B4%5D%5Bsearchable%5D=true&columns%5B4%5D%5Borderable%5D=true&columns%5B4%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B4%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B5%5D%5Bdata%5D=shop_name&columns%5B5%5D%5Bname%5D=shop&columns%5B5%5D%5Bsearchable%5D=true&columns%5B5%5D%5Borderable%5D=true&columns%5B5%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B5%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B6%5D%5Bdata%5D=quantity&columns%5B6%5D%5Bname%5D=quantity&columns%5B6%5D%5Bsearchable%5D=true&columns%5B6%5D%5Borderable%5D=true&columns%5B6%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B6%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B7%5D%5Bdata%5D=total_amount&columns%5B7%5D%5Bname%5D=total_amount&columns%5B7%5D%5Bsearchable%5D=true&columns%5B7%5D%5Borderable%5D=true&columns%5B7%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B7%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B8%5D%5Bdata%5D=delivery_fees&columns%5B8%5D%5Bname%5D=delivery_fees&columns%5B8%5D%5Bsearchable%5D=true&columns%5B8%5D%5Borderable%5D=true&columns%5B8%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B8%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B9%5D%5Bdata%5D=markup_delivery_fees&columns%5B9%5D%5Bname%5D=markup_delivery_fees&columns%5B9%5D%5Bsearchable%5D=true&columns%5B9%5D%5Borderable%5D=true&columns%5B9%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B9%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B10%5D%5Bdata%5D=remark&columns%5B10%5D%5Bname%5D=remark&columns%5B10%5D%5Bsearchable%5D=true&columns%5B10%5D%5Borderable%5D=true&columns%5B10%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B10%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B11%5D%5Bdata%5D=item_type&columns%5B11%5D%5Bname%5D=item_type&columns%5B11%5D%5Bsearchable%5D=true&columns%5B11%5D%5Borderable%5D=true&columns%5B11%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B11%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B12%5D%5Bdata%5D=full_address&columns%5B12%5D%5Bname%5D=full_address&columns%5B12%5D%5Bsearchable%5D=true&columns%5B12%5D%5Borderable%5D=true&columns%5B12%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B12%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B13%5D%5Bdata%5D=schedule_date&columns%5B13%5D%5Bname%5D=schedule_date&columns%5B13%5D%5Bsearchable%5D=true&columns%5B13%5D%5Borderable%5D=true&columns%5B13%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B13%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B14%5D%5Bdata%5D=type&columns%5B14%5D%5Bname%5D=type&columns%5B14%5D%5Bsearchable%5D=true&columns%5B14%5D%5Borderable%5D=true&columns%5B14%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B14%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B15%5D%5Bdata%5D=collection_method&columns%5B15%5D%5Bname%5D=collection_method&columns%5B15%5D%5Bsearchable%5D=true&columns%5B15%5D%5Borderable%5D=true&columns%5B15%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B15%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B16%5D%5Bdata%5D=last_updated_by_name&columns%5B16%5D%5Bname%5D=last_updated_by&columns%5B16%5D%5Bsearchable%5D=true&columns%5B16%5D%5Borderable%5D=true&columns%5B16%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B16%5D%5Bsearch%5D%5Bregex%5D=false&draw=1&length=10&order%5B0%5D%5Bcolumn%5D=0&order%5B0%5D%5Bdir%5D=asc&search%5Bvalue%5D=&search%5Bregex%5D=false&start=0";
        $response = $this->getJson($order_history_dt_url);
        $response->assertStatus(200)->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data' => [
                '*' => [
                    'id',
                    'order_code',
                    'customer_name',
                    'customer_phone_number',
                    'township_id',
                    'rider_id',
                    'shop_id',
                    'quantity',
                    'total_amount',
                    'delivery_fees',
                    'markup_delivery_fees',
                    'remark',
                    'status',
                    'item_type',
                    'full_address',
                    'schedule_date',
                    'type',
                    'collection_method',
                    'proof_of_payment',
                    'last_updated_by',
                    'deleted_at',
                    'created_at',
                    'updated_at',
                    'city_id',
                    'township_name',
                    'shop_name',
                    'rider_name',
                    'last_updated_by_name',
                    'DT_RowIndex'
                ]
            ]
        ]);

        $shop_user_dt_url = "/riders/get-pending-orders-by-rider-id/". $rider_id ."?_=1680255786487&columns%5B0%5D%5Bdata%5D=DT_RowIndex&columns%5B0%5D%5Bname%5D=id&columns%5B0%5D%5Bsearchable%5D=true&columns%5B0%5D%5Borderable%5D=true&columns%5B0%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B0%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B1%5D%5Bdata%5D=order_code&columns%5B1%5D%5Bname%5D=order_code&columns%5B1%5D%5Bsearchable%5D=true&columns%5B1%5D%5Borderable%5D=true&columns%5B1%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B1%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B2%5D%5Bdata%5D=customer_name&columns%5B2%5D%5Bname%5D=customer_name&columns%5B2%5D%5Bsearchable%5D=true&columns%5B2%5D%5Borderable%5D=true&columns%5B2%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B2%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B3%5D%5Bdata%5D=customer_phone_number&columns%5B3%5D%5Bname%5D=customer_phone_number&columns%5B3%5D%5Bsearchable%5D=true&columns%5B3%5D%5Borderable%5D=true&columns%5B3%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B3%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B4%5D%5Bdata%5D=township_name&columns%5B4%5D%5Bname%5D=township&columns%5B4%5D%5Bsearchable%5D=true&columns%5B4%5D%5Borderable%5D=true&columns%5B4%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B4%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B5%5D%5Bdata%5D=shop_name&columns%5B5%5D%5Bname%5D=shop&columns%5B5%5D%5Bsearchable%5D=true&columns%5B5%5D%5Borderable%5D=true&columns%5B5%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B5%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B6%5D%5Bdata%5D=quantity&columns%5B6%5D%5Bname%5D=quantity&columns%5B6%5D%5Bsearchable%5D=true&columns%5B6%5D%5Borderable%5D=true&columns%5B6%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B6%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B7%5D%5Bdata%5D=total_amount&columns%5B7%5D%5Bname%5D=total_amount&columns%5B7%5D%5Bsearchable%5D=true&columns%5B7%5D%5Borderable%5D=true&columns%5B7%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B7%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B8%5D%5Bdata%5D=delivery_fees&columns%5B8%5D%5Bname%5D=delivery_fees&columns%5B8%5D%5Bsearchable%5D=true&columns%5B8%5D%5Borderable%5D=true&columns%5B8%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B8%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B9%5D%5Bdata%5D=markup_delivery_fees&columns%5B9%5D%5Bname%5D=markup_delivery_fees&columns%5B9%5D%5Bsearchable%5D=true&columns%5B9%5D%5Borderable%5D=true&columns%5B9%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B9%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B10%5D%5Bdata%5D=remark&columns%5B10%5D%5Bname%5D=remark&columns%5B10%5D%5Bsearchable%5D=true&columns%5B10%5D%5Borderable%5D=true&columns%5B10%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B10%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B11%5D%5Bdata%5D=item_type&columns%5B11%5D%5Bname%5D=item_type&columns%5B11%5D%5Bsearchable%5D=true&columns%5B11%5D%5Borderable%5D=true&columns%5B11%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B11%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B12%5D%5Bdata%5D=full_address&columns%5B12%5D%5Bname%5D=full_address&columns%5B12%5D%5Bsearchable%5D=true&columns%5B12%5D%5Borderable%5D=true&columns%5B12%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B12%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B13%5D%5Bdata%5D=schedule_date&columns%5B13%5D%5Bname%5D=schedule_date&columns%5B13%5D%5Bsearchable%5D=true&columns%5B13%5D%5Borderable%5D=true&columns%5B13%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B13%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B14%5D%5Bdata%5D=type&columns%5B14%5D%5Bname%5D=type&columns%5B14%5D%5Bsearchable%5D=true&columns%5B14%5D%5Borderable%5D=true&columns%5B14%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B14%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B15%5D%5Bdata%5D=collection_method&columns%5B15%5D%5Bname%5D=collection_method&columns%5B15%5D%5Bsearchable%5D=true&columns%5B15%5D%5Borderable%5D=true&columns%5B15%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B15%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B16%5D%5Bdata%5D=last_updated_by_name&columns%5B16%5D%5Bname%5D=last_updated_by&columns%5B16%5D%5Bsearchable%5D=true&columns%5B16%5D%5Borderable%5D=true&columns%5B16%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B16%5D%5Bsearch%5D%5Bregex%5D=false&draw=1&length=10&order%5B0%5D%5Bcolumn%5D=0&order%5B0%5D%5Bdir%5D=asc&search%5Bvalue%5D=&search%5Bregex%5D=false&start=0";
        $response = $this->getJson($shop_user_dt_url);
        $response->assertStatus(200)->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data' => [
                '*' => [
                    'id',
                    'order_code',
                    'customer_name',
                    'customer_phone_number',
                    'township_id',
                    'rider_id',
                    'shop_id',
                    'quantity',
                    'total_amount',
                    'delivery_fees',
                    'markup_delivery_fees',
                    'remark',
                    'status',
                    'item_type',
                    'full_address',
                    'schedule_date',
                    'type',
                    'collection_method',
                    'proof_of_payment',
                    'last_updated_by',
                    'deleted_at',
                    'created_at',
                    'updated_at',
                    'city_id',
                    'township_name',
                    'shop_name',
                    'rider_name',
                    'last_updated_by_name',
                    'DT_RowIndex'
                ]
            ]
        ]);
    }

    public function test_update_rider(): void
    {
        $out = "test_update_rider";
        var_dump($out);
        $admin = $this->get_authenticated_user();

        $rider_id = Rider::all()->random()->id;
        $password = $this->faker->password(8,20);
        $township_id = Township::all()->random()->id;
        $response = $this->put('/riders/' . $rider_id, [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'phone_number' => $this->faker->phoneNumber,
            'password' => $password,
            'password_confirmation' =>$password,
            'township_id' => [
                $township_id
            ],
            'id' => $rider_id
        ]);
        $response->assertStatus(302)
        ->assertRedirect('/riders/' . $rider_id);

    }

    public function test_delete_rider(): void 
    {   
        $out = "test_delete_rider";
        var_dump($out);
        $admin = $this->get_authenticated_user();

        $rider_id = Rider::all()->random()->id;
        
        $response = $this->delete('/riders/' . $rider_id);
        $response->assertStatus(302)
        ->assertRedirect('/riders');

    }
}
