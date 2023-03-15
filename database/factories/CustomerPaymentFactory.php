<?php

namespace Database\Factories;

use App\Models\CustomerPayment;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerPayment>
 */
class CustomerPaymentFactory extends Factory
{   
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
   protected $model = CustomerPayment::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::all()->random()->id,
            'amount' => fake()->randomDigit(),
            'type' => array_rand(['fully_paid', 'delivery_fees_only', 'remaining_amount']),
            'proof_of_payment' => fake()->image(),
            'paid_at' => fake()->date(),
            'last_updated_by' => User::all()->random()->id,
        ];
    }
}

