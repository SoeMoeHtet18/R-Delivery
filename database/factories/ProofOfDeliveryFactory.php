<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\ProofOfDelivery;
use App\Models\Rider;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProofOfDelivery>
 */
class ProofOfDeliveryFactory extends Factory
{   
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProofOfDelivery::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::all()->random()->id,
            'image' => fake()->image(),
            'delivered_date' => fake()->date(),
            'rider_id' => Rider::all()->random()->id,
            'last_updated_by' => User::all()->random()->id,
        ];
    }
}