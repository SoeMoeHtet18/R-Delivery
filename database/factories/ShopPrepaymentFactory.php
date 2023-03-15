<?php

namespace Database\Factories;

use App\Models\Shop;
use App\Models\ShopPrepayment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ShopPrepayment>
 */
class ShopPrepaymentFactory extends Factory
{   
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ShopPrepayment::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'shop_id' => Shop::all()->random()->id,
            'amount' => fake()->randomDigit(),
            'paid_by' => fake()->name(),
        ];
    }
}
