<?php

namespace Tests\Feature;

use App\Models\Shop;
use App\Models\TransactionsForShop;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransactionsForShopTest extends TestCase
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
    public function test_transactions_for_shop_web(): void
    {
        $out = "test_transactions_for_shop_web";
        var_dump($out);
        $admin = $this->get_authenticated_user();

        $response = $this->get('/transactions-for-shop');
        $response->assertStatus(200);

        $dt_url = "/ajax-get-transactions-data?_=1680243786070&amount=&columns%5B0%5D%5Bdata%5D=DT_RowIndex&columns%5B0%5D%5Bname%5D=id&columns%5B0%5D%5Bsearchable%5D=true&columns%5B0%5D%5Borderable%5D=true&columns%5B0%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B0%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B1%5D%5Bdata%5D=shop_name&columns%5B1%5D%5Bname%5D=shop_name&columns%5B1%5D%5Bsearchable%5D=true&columns%5B1%5D%5Borderable%5D=true&columns%5B1%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B1%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B2%5D%5Bdata%5D=amount&columns%5B2%5D%5Bname%5D=amount&columns%5B2%5D%5Bsearchable%5D=true&columns%5B2%5D%5Borderable%5D=true&columns%5B2%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B2%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B3%5D%5Bdata%5D=type&columns%5B3%5D%5Bname%5D=type&columns%5B3%5D%5Bsearchable%5D=true&columns%5B3%5D%5Borderable%5D=true&columns%5B3%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B3%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B4%5D%5Bdata%5D=paid_by&columns%5B4%5D%5Bname%5D=paid_by&columns%5B4%5D%5Bsearchable%5D=true&columns%5B4%5D%5Borderable%5D=true&columns%5B4%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B4%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B5%5D%5Bdata%5D=action&columns%5B5%5D%5Bname%5D=action&columns%5B5%5D%5Bsearchable%5D=false&columns%5B5%5D%5Borderable%5D=false&columns%5B5%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B5%5D%5Bsearch%5D%5Bregex%5D=false&draw=1&length=10&order%5B0%5D%5Bcolumn%5D=0&order%5B0%5D%5Bdir%5D=asc&paid_by=&search%5Bvalue%5D=&search%5Bregex%5D=false&shop_name=&start=0&type=";
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
                    'paid_by',
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

    public function test_get_create_transactions_for_shop_web(): void
    {
        $out = " test_get_create_transactions_for_shop_web";
        var_dump($out);
        $admin = $this->get_authenticated_user();
        
        $response = $this->get('/transactions-for-shop/create');
        $response->assertStatus(200);
    }

    public function  test_store_transactions_for_shop(): void
    {
        $out = " test_store_transactions_for_shop";
        var_dump($out);
        $admin = $this->get_authenticated_user();

        $type = ['fully_payment', 'loan_payment'];
        $rand_type = $type[array_rand($type)];

        $response = $this->post('/transactions-for-shop', [
            'shop_id' => Shop::all()->random()->id,
            'amount' => $this->faker->randomDigit,
            'type' => $rand_type,
            'paid_by' => User::all()->random()->id,
            'image' => $this->faker->image,
        ]);
        $response->assertStatus(302);
    }

    public function  test_transactions_for_shop_detail_web(): void 
    {
        $out = " test_transactions_for_shop_detail_web";
        var_dump($out);
        $admin = $this->get_authenticated_user();

        $transactions_for_shop_id = TransactionsForShop::all()->random()->id;
        $response = $this->get('/transactions-for-shop/' . $transactions_for_shop_id);
        $response->assertStatus(200);
    }

    public function  test_update_transactions_for_shop(): void
    {
        $out = " test_update_transactions_for_shop";
        var_dump($out);
        $admin = $this->get_authenticated_user();

        $type = ['fully_payment', 'loan_payment'];
        $rand_type = $type[array_rand($type)];

        $transactions_for_shop_id = TransactionsForShop::all()->random()->id;
        $response = $this->put('/transactions-for-shop/' . $transactions_for_shop_id , [
            'shop_id' => Shop::all()->random()->id,
            'amount' => $this->faker->randomDigit,
            'type' => $rand_type,
            'paid_by' => User::all()->random()->id,
            'image' => $this->faker->image,
        ]);
        $response->assertStatus(302);
    }

    public function  test_delete_transactions_for_shop(): void 
    {
        $out = " test_delete_transactions_for_shop";
        var_dump($out);
        $admin = $this->get_authenticated_user();

        $transactions_for_shop_id = TransactionsForShop::all()->random()->id;
        $response = $this->delete('/transactions-for-shop/' . $transactions_for_shop_id);
        $response->assertStatus(302);
    }
}
