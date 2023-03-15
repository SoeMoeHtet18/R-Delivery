<?php

namespace Database\Factories;

use App\Models\ShopPayment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ShopPayment>
 */
class ShopPaymentFactory extends Factory
{   
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ShopPayment::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'amount' => fake()->randomDigit(),
            'image' => fake()->image(),
            'type' => Str::random(6),
        ];
    }
}
