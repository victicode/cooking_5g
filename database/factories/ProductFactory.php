<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition()
    {
        return [
            'title' => fake()->words(3, true),
            'description' => fake()->paragraph(2),
            'short_description' => fake()->text(10),
            'stock' => fake()->numberBetween(0, 100),
            'type_of_unit' => 'KG',
            'is_dismantling' => 0,
            'created_by' => 1,
            'updated_by' => 1,
        ];
    }
}
