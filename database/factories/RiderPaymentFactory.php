<?php

namespace Database\Factories;

use App\Models\Rider;
use App\Models\RiderPayment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RiderPayment>
 */
class RiderPaymentFactory extends Factory
{   
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RiderPayment::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'rider_id' => Rider::all()->random()->id,
            'total_routine' => fake()->randomNumber(),
            'total_amount' => fake()->randomDigit(),
        ];
    }
}
