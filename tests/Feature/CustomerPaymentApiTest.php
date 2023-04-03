<?php

namespace Tests\Feature;

use App\Models\CustomerPayment;
use App\Models\Order;
use App\Models\Rider;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerPaymentApiTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    public function get_authenticated_rider()
    {
        $rider_phone_number = config('app.admin_phone_number');
        $rider = Rider::select('*')->where('phone_number',$rider_phone_number)->first();
        $this->actingAs($rider, 'rider-api');
        return $rider;
    }
    /**
     * A basic feature test example.
     */
    public function test_insert_customer_payment_by_rider(): void
    {
        $this->withoutExceptionHandling();
        $out = 'test_insert_customer_payment_by_rider';
        var_dump($out);
        $rider = $this->get_authenticated_rider();
        
        $type = ['fully_paid', 'remaining_amount', 'delivery_fees_only'];
        $rand_type = $type[array_rand($type)];

        $response = $this->postJson('/api/create-customer-payment-by-rider', [
            'order_id' => Order::all()->random()->id,
            'amount' => $this->faker->randomDigit,
            'type' => $rand_type,
            'paid_at' => Carbon::now()->format('Y-m-d'),
            'proof_of_payment' => $this->faker->image,
            'description' => $this->faker->text
        ]);
        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                'order_id',
                'amount',
                'type',
                'proof_of_payment',
                'paid_at',
                'last_updated_by',
                'updated_at',
                'created_at',
                'id'
            ],
            'message',
            'status'
        ]);
    }

    public function test_customer_payment_detail()
    {
        $out = 'test_customer_payment_detail';
        var_dump($out);
        $rider = $this->get_authenticated_rider();

        $customer_payment_id = CustomerPayment::all()->random()->id;
        $response = $this->getJson('/api/customer-payment/' . $customer_payment_id);
        $response->assertStatus(200);
    }

    public function test_customer_payment_list()
    {
        $out = 'test_customer_payment_detail';
        var_dump($out);
        $rider = $this->get_authenticated_rider();

        $response = $this->getJson('/api/get-customer-payment-list');
        $response->assertStatus(200);
    }
}
