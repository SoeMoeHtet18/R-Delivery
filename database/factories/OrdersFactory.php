<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Rider;
use App\Models\Shop;
use App\Models\Township;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class OrdersFactory extends Factory
{   
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_code' => fake()->uuid(),
            'customer_name' => fake()->name(),
            'customer_phone_number' => fake()->unique()->phoneNumber(),
            'township_id' => Township::all()->random()->id,
            'rider_id' => Rider::all()->random()->id,
            'shop_id' => Shop::all()->random()->id,
            'quantity' => fake()->random_int(1, 10),
            'total_amount' => fake()->randomDigit(),
            'delivery_fees' => fake()->randomDigit(),
            'markup_delivery_fees' => fake()->randomDigit(),
            'remark' => fake()->text(),
            'status' => array_rand([ 'cancel', 'date_changed', 'delivering', 'pending', 'success']),
            'item_type' => array_rand(['food', 'groceries', 'clothing', 'electronics']),
            'full_address' => fake()->address(),
            'schedule_date' => fake()->date(),
            'type' => array_rand(['standard', 'express', 'door_to_door']),
            'collection_method' => array_rand(['pick_up', 'drop_off']),
            'proof_of_payment' => fake()->image(),
            'last_updated_by' => Str::randomNumber(2),
        ];
    }
}
