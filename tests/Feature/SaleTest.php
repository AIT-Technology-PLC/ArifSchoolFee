<?php

namespace Tests\Feature;

use App\Models\Feature;
use App\Models\Integration;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SaleTest extends TestCase
{
    protected function setup(): void
    {
        parent::setUp();

        Feature::enableOrDisableForCompany('Sale Management', $this->company->id, 1);
    }

    public function test_user_with_pos_integration_can_approve_a_sale(): void
    {
        $url = str(fake()->url())->replaceEnd('/', '')->toString();

        Http::fake([
            "$url/*" => Http::response([
                'Success' => true,
                'Message' => 'success',
            ], 200),
        ]);

        $this->company->integrations()->syncWithPivotValues(
            Integration::firstWhere('name', 'Point of Sale'), ['is_enabled' => 1]
        );

        $this->user->warehouse->update([
            'pos_provider' => 'Peds',
            'host_address' => $url,
        ]);

        $this->user->syncPermissions(['Approve Sale']);

        $sale = Sale::factory()->has(
            SaleDetail::factory()->count(3)->state(['warehouse_id' => $this->user->warehouse_id])
        )->create();

        $response = $this->post(route('sales.approve', $sale));

        $sale->refresh();

        $response->assertStatus(302)->assertSessionHas('successMessage');

        $this
            ->assertDatabaseHas('sales', ['id' => $sale->id])
            ->assertDatabaseHas('sale_details', ['id' => $sale->saleDetails()->first()->id])
            ->assertDatabaseCount('sale_details', $sale->saleDetails()->count())
            ->assertTrue($sale->isApproved());
    }
}
