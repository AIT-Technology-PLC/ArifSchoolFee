<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaleDetailFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_id' => Product::factory()->create()->id,
            // 'warehouse_id' => null,
            'unit_price' => fake()->randomFloat(),
            'quantity' => fake()->randomFloat(),
            'description' => null,
            // 'merchandise_batch_id' => null
        ];
    }
}
