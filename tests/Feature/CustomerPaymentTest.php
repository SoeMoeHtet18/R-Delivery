<?php

namespace Tests\Feature;

use App\Models\CustomerPayment;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CustomerPaymentTest extends TestCase
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
    public function test_customer_payment_web(): void
    {
        $out = "test_customer_payment_web";
        var_dump($out);
        $admin = $this->get_authenticated_user();

        $response = $this->get('/customer-payments');
        $response->assertStatus(200);

        $dt_url = "/ajax-get-customer-payment-data?_=1680240505783&amount=&columns%5B0%5D%5Bdata%5D=DT_RowIndex&columns%5B0%5D%5Bname%5D=id&columns%5B0%5D%5Bsearchable%5D=true&columns%5B0%5D%5Borderable%5D=true&columns%5B0%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B0%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B1%5D%5Bdata%5D=order_code&columns%5B1%5D%5Bname%5D=order_code&columns%5B1%5D%5Bsearchable%5D=true&columns%5B1%5D%5Borderable%5D=true&columns%5B1%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B1%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B2%5D%5Bdata%5D=amount&columns%5B2%5D%5Bname%5D=amount&columns%5B2%5D%5Bsearchable%5D=true&columns%5B2%5D%5Borderable%5D=true&columns%5B2%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B2%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B3%5D%5Bdata%5D=type&columns%5B3%5D%5Bname%5D=type&columns%5B3%5D%5Bsearchable%5D=true&columns%5B3%5D%5Borderable%5D=true&columns%5B3%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B3%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B4%5D%5Bdata%5D=paid_at&columns%5B4%5D%5Bname%5D=paid_at&columns%5B4%5D%5Bsearchable%5D=true&columns%5B4%5D%5Borderable%5D=true&columns%5B4%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B4%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B5%5D%5Bdata%5D=action&columns%5B5%5D%5Bname%5D=action&columns%5B5%5D%5Bsearchable%5D=false&columns%5B5%5D%5Borderable%5D=false&columns%5B5%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B5%5D%5Bsearch%5D%5Bregex%5D=false&draw=1&item_type=&length=10&order%5B0%5D%5Bcolumn%5D=0&order%5B0%5D%5Bdir%5D=asc&order_code=&search%5Bvalue%5D=&search%5Bregex%5D=false&start=0";
        $response = $this->getJson($dt_url);
        $response->assertStatus(200)->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data' => [
                '*' => [
                    'id',
                    "order_id",
                    "amount",
                    "type",
                    "proof_of_payment",
                    "paid_at",
                    "last_updated_by",
                    "deleted_at",
                    "created_at",
                    "updated_at",
                    "order_code",
                    'action',
                    'DT_RowIndex'
                ]
            ]
        ]);
    }

    public function  test_get_create_customer_payment_web(): void
    {
        $out = " test_get_create_customer_payment_web";
        var_dump($out);
        $admin = $this->get_authenticated_user();
        
        $response = $this->get('/customer-payments/create');
        $response->assertStatus(200);
    }

    public function  test_store_customer_payment(): void
    {
        $out = " test_store_customer_payment";
        var_dump($out);
        $admin = $this->get_authenticated_user();

        $type = ['fully_paid', 'remaining_amount', 'delivery_fees_only'];
        $rand_type = $type[array_rand($type)];
        
        $file = UploadedFile::fake()->create('test-image.jpg', 200, 'image/jpeg');
        
        $response = $this->post('/customer-payments', [
            'order_id' => Order::all()->random()->id,
            'amount' => $this->faker->randomDigit,
            'type' => $rand_type,
            'paid_at' => Carbon::now(),
            'proof_of_payment' => $file,
            'description' => $this->faker->text
        ]);
        $response->assertStatus(302)
            ->assertRedirect('/customer-payments');
    }

    public function  test_customer_payment_detail_web(): void 
    {
        $out = " test_customer_payment_detail_web";
        var_dump($out);
        $admin = $this->get_authenticated_user();

        $customer_payment_id = CustomerPayment::all()->random()->id;
        $response = $this->get('/customer-payments/' . $customer_payment_id);
        $response->assertStatus(200);
    }

    public function  test_update_customer_payment(): void
    {
        $out = " test_update_customer_payment";
        var_dump($out);
        $admin = $this->get_authenticated_user();

        $type = ['fully_paid', 'remaining_amount', 'delivery_fees_only'];
        $rand_type = $type[array_rand($type)];

        $customer_payment_id = CustomerPayment::all()->random()->id;

        $file = UploadedFile::fake()->create('test-image.jpg', 200, 'image/jpeg');

        $response = $this->put('/customer-payments/' . $customer_payment_id, [
            'order_id' => Order::all()->random()->id,
            'amount' => $this->faker->randomDigit,
            'type' => $rand_type,
            'paid_at' => Carbon::now(),
            'proof_of_payment' => $file,
            'description' => $this->faker->text
        ]);
        $response->assertStatus(302)
            ->assertRedirect('/customer-payments');
    }

    public function  test_delete_customer_payment(): void 
    {
        $out = " test_delete_customer_payment";
        var_dump($out);
        $admin = $this->get_authenticated_user();

        $customer_payment_id = CustomerPayment::all()->random()->id;
        $response = $this->delete('/customer-payments/' . $customer_payment_id);
        $response->assertStatus(302)
            ->assertRedirect('/customer-payments');
    }
}
