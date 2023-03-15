<?php

namespace Database\Factories;

use App\Models\Shop;
use App\Models\TransactionsForShop;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ShopPrepayment>
 */
class TransactionsForShopFactory extends Factory
{   
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TransactionsForShop::class;
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
            'image' => fake()->image(),
            'type' => array_rand(['fully_payment','loan_payment']),
            'paid_by' => fake()->name(),
        ];
    }
}
