<?php

namespace Tests\Feature;

use App\Models\Feature;
use App\Models\ProductCategory;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    protected function setup(): void
    {
        parent::setUp();

        Feature::enableOrDisableForCompany('Product Management', $this->company->id, 1);
    }

    public function test_user_can_access_index_view(): void
    {
        $this->user->syncPermissions(['Read Product']);

        $response = $this->get(route('categories.index'));

        $response
            ->assertStatus(200)
            ->assertViewIs('categories.index')
            ->assertViewHas('totalProductCategories', 0);
    }

    public function test_user_can_see_create_category_button_on_index_view(): void
    {
        $this->user->syncPermissions(['Read Product', 'Create Product']);

        $response = $this->get(route('categories.index'));

        $response
            ->assertSee('Create Category')
            ->assertSee(route('categories.create'))
            ->assertStatus(200);
    }

    public function test_user_cannot_see_create_category_button_on_index_view(): void
    {
        $this->user->syncPermissions(['Read Product']);

        $response = $this->get(route('categories.index'));

        $response
            ->assertDontSee('Create Category')
            ->assertDontSee(route('categories.create'))
            ->assertStatus(200);
    }

    public function test_user_can_access_create_view(): void
    {
        $this->user->syncPermissions(['Create Product']);

        $response = $this->get(route('categories.create'));

        $response
            ->assertStatus(200)
            ->assertViewIs('categories.create');
    }

    public function test_user_can_create_category(): void
    {
        $this->user->syncPermissions(['Create Product']);

        $data = [
            'name' => 'Computers',
        ];

        $response = $this->post(route('categories.store'), $data);

        $this->assertDatabaseHas('product_categories', $data);

        $response
            ->assertStatus(302)
            ->assertRedirectToRoute('categories.index');
    }

    public function test_user_failed_create_category_validation(): void
    {
        $this->user->syncPermissions(['Create Product']);

        $response = $this->post(route('categories.store'), ['name' => '']);

        $response
            ->assertStatus(302)
            ->assertInvalid(['name']);
    }

    public function test_user_can_access_edit_category_view(): void
    {
        $this->user->syncPermissions(['Update Product']);

        $category = ProductCategory::factory()->create();

        $response = $this->get(route('categories.edit', $category));

        $response
            ->assertStatus(200)
            ->assertViewHas('category', $category)
            ->assertViewIs('categories.edit');
    }

    public function test_user_can_update_a_category(): void
    {
        $this->user->syncPermissions(['Update Product']);

        $category = ProductCategory::factory()->create(['name' => 'Computer']);

        $this->assertDatabaseHas('product_categories', ['name' => $category->name]);

        $updateData = ['name' => 'Soft Drinks'];

        $response = $this->patch(route('categories.update', $category), $updateData);

        $this->assertDatabaseHas('product_categories', $updateData);

        $response
            ->assertRedirectToRoute('categories.index')
            ->assertStatus(302);
    }

    public function test_user_can_softdelete_a_category(): void
    {
        $this->user->syncPermissions(['Delete Product']);

        $category = ProductCategory::factory()->create();

        $response = $this->delete(route('categories.destroy', $category));

        $this->assertSoftDeleted('product_categories', $category->only('id'));

        $response
            ->assertSessionHas('deleted', 'Deleted successfully.')
            ->assertStatus(302);
    }

    public function test_user_can_import_categories()
    {
        Excel::fake();

        $this->user->syncPermissions(['Import Product']);

        $response = $this->post(route('categories.import'), [
            'file' => UploadedFile::fake()->create('categories.xlsx'),
        ]);

        Excel::assertImported('categories.xlsx');

        $response
            ->assertSessionHas('imported', __('messages.file_imported'))
            ->assertStatus(302);
    }
}
