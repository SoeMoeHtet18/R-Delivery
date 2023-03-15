<?php

namespace Database\Factories;

use App\Models\Township;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class TownshipsFactory extends Factory
{   
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Township::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
        ];
    }
}