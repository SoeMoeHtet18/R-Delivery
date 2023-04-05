<?php

namespace Tests\Feature;

use App\Models\Shop;
use App\Models\ShopPayment;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ShopPaymentTest extends TestCase
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
    public function test_shop_payment_web(): void
    {
        $out = "test_shop_payment_web";
        var_dump($out);
        $admin = $this->get_authenticated_user();

        $response = $this->get('/shoppayments');
        $response->assertStatus(200);

        $dt_url = "/ajax-get-shop-payment-data?_=1680235797559&amount=&columns%5B0%5D%5Bdata%5D=DT_RowIndex&columns%5B0%5D%5Bname%5D=id&columns%5B0%5D%5Bsearchable%5D=true&columns%5B0%5D%5Borderable%5D=true&columns%5B0%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B0%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B1%5D%5Bdata%5D=shop_name&columns%5B1%5D%5Bname%5D=shop_name&columns%5B1%5D%5Bsearchable%5D=true&columns%5B1%5D%5Borderable%5D=true&columns%5B1%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B1%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B2%5D%5Bdata%5D=amount&columns%5B2%5D%5Bname%5D=amount&columns%5B2%5D%5Bsearchable%5D=true&columns%5B2%5D%5Borderable%5D=true&columns%5B2%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B2%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B3%5D%5Bdata%5D=type&columns%5B3%5D%5Bname%5D=type&columns%5B3%5D%5Bsearchable%5D=true&columns%5B3%5D%5Borderable%5D=true&columns%5B3%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B3%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B4%5D%5Bdata%5D=action&columns%5B4%5D%5Bname%5D=action&columns%5B4%5D%5Bsearchable%5D=false&columns%5B4%5D%5Borderable%5D=false&columns%5B4%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B4%5D%5Bsearch%5D%5Bregex%5D=false&draw=1&item_type=&length=10&order%5B0%5D%5Bcolumn%5D=0&order%5B0%5D%5Bdir%5D=asc&search%5Bvalue%5D=&search%5Bregex%5D=false&shop_name=&start=0";
        $response = $this->getJson($dt_url);
        $response->assertStatus(200)->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data' => [
                '*' => [
                    'id',
                    'shop_id',
                    'amount',
                    'image',
                    'type',
                    'deleted_at',
                    'created_at',
                    'updated_at',
                    'shop_name',
                    'action',
                    'DT_RowIndex'
                ]
            ]
        ]);
    }

    public function test_get_create_shop_payment_web(): void
    {
        $out = " test_get_create_shop_payment_web";
        var_dump($out);
        $admin = $this->get_authenticated_user();
        
        $response = $this->get('/shoppayments/create');
        $response->assertStatus(200);
    }

    public function  test_store_shop_payment(): void
    {
        $out = " test_store_shop_payment";
        var_dump($out);
        $admin = $this->get_authenticated_user();

        $type = ['delivery_payment', 'remaining_payment'];
        $rand_type = $type[array_rand($type)];

        $response = $this->post('/shoppayments', [
            'shop_id' => Shop::all()->random()->id,
            'amount' => $this->faker->randomDigit,
            'image' => $this->faker->image,
            'type' => $rand_type
        ]);
        $response->assertStatus(302);
    }

    public function  test_shop_payment_detail_web(): void 
    {
        $out = " test_shop_payment_detail_web";
        var_dump($out);
        $admin = $this->get_authenticated_user();

        $shop_payment_id = ShopPayment::all()->random()->id;
        $response = $this->get('/shoppayments/' . $shop_payment_id);
        $response->assertStatus(200);
    }

    public function  test_update_shop_payment(): void
    {
        $out = " test_update_shop_payment";
        var_dump($out);
        $admin = $this->get_authenticated_user();

        $type = ['delivery_payment', 'remaining_payment'];
        $rand_type = $type[array_rand($type)];

        $file = UploadedFile::fake()->image('test.jpg');
        $file_size = $file->size(235.354);

        $shop_payment_id = ShopPayment::all()->random()->id;
        $response = $this->put('/shoppayments/' . $shop_payment_id , [
            'shop_id' => Shop::all()->random()->id,
            'amount' => $this->faker->randomDigit,
            'image' => [
                'name' => $file,
                'size' => $file_size
            ],
            'type' => $rand_type
        ]);
        $response->assertStatus(302);
    }

    public function  test_delete_shop_payment(): void 
    {
        $out = " test_delete_shop_payment";
        var_dump($out);
        $admin = $this->get_authenticated_user();

        $shop_payment_id = ShopPayment::all()->random()->id;
        $response = $this->delete('/shoppayments/' . $shop_payment_id);
        $response->assertStatus(302);
    }
}