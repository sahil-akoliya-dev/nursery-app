<?php

namespace Tests\Feature\Feature\Api;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_all_categories(): void
    {
        Category::factory()->count(5)->create();

        $response = $this->getJson('/api/categories');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => ['id', 'name', 'slug', 'description']
                ]
            ])
            ->assertJson(['success' => true]);
    }

    public function test_can_get_category_tree(): void
    {
        $parent = Category::factory()->create(['parent_id' => null]);
        Category::factory()->count(3)->create(['parent_id' => $parent->id]);

        $response = $this->getJson('/api/categories/tree');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_can_show_single_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->getJson('/api/categories/' . $category->id);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => ['id' => $category->id]
            ]);
    }

    public function test_showing_non_existent_category_returns_404(): void
    {
        $response = $this->getJson('/api/categories/99999');
        $response->assertStatus(404);
    }
}
