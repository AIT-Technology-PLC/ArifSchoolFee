<?php

namespace Database\Factories;

use App\Models\ProductCategory;
use App\Models\Tax;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->text(255),
            'type' => 'Finished Goods',
            'code' => fake()->text(255),
            'unit_of_measurement' => fake()->text(10),
            'min_on_hand' => fake()->randomFloat(),
            'description' => fake()->sentence(),
            'properties' => [],
            'product_category_id' => ProductCategory::factory()->create()->id,
            // 'supplier_id' => ['nullable', 'integer', new MustBelongToCompany('suppliers')],
            // 'brand_id' => ['nullable', 'integer', new MustBelongToCompany('brands')],
            'tax_id' => Tax::all()->random()->id,
            'is_batchable' => 0,
            'batch_priority' => null,
            'is_active' => 1,
            'is_active_for_sale' => 1,
            'is_active_for_purchase' => 1,
            'is_active_for_job' => 1,
            'is_product_single' => 1,
            'inventory_valuation_method' => collect(['fifo', 'lifo', 'average'])->random(),
            'profit_margin_type' => collect(['amount', 'percent'])->random(),
            'profit_margin_amount' => fake()->randomFloat(min: 0, max: 100),

        ];
    }

    public function active(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => 1,
                'is_active_for_sale' => 1,
                'is_active_for_purchase' => 1,
                'is_active_for_job' => 1,
            ];
        });
    }

    public function activeForSale(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => 1,
                'is_active_for_sale' => 1,
                'is_active_for_purchase' => 0,
                'is_active_for_job' => 0,
            ];
        });
    }

    public function activeForPurchase(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => 1,
                'is_active_for_sale' => 0,
                'is_active_for_purchase' => 1,
                'is_active_for_job' => 0,
            ];
        });
    }

    public function activeForJob(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => 1,
                'is_active_for_sale' => 0,
                'is_active_for_purchase' => 0,
                'is_active_for_job' => 1,
            ];
        });
    }

    public function bundle(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'is_product_single' => 0,
            ];
        });
    }

    public function batchable(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'is_batchable' => 1,
                'batch_priority' => collect(['fifo', 'lifo'])->random(),
            ];
        });
    }

    public function inventoryType(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => collect(['Finished Goods', 'Raw Material'])->random(),
                'is_product_single' => 1,
            ];
        });
    }

    public function noninventoryType(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => collect(['Non-inventory Product', 'Services'])->random(),
                'is_product_single' => 1,
            ];
        });
    }
}
