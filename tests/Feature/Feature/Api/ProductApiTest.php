<?php

namespace Tests\Feature\Feature\Api;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test listing products with pagination
     */
    public function test_can_list_products_with_pagination(): void
    {
        // Create test products
        Product::factory()->count(15)->create();

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'price',
                        'stock',
                        'is_active',
                        'category',
                        'images',
                    ]
                ],
                'meta' => [
                    'current_page',
                    'last_page',
                    'per_page',
                    'total',
                ]
            ])
            ->assertJson(['success' => true]);
    }

    /**
     * Test filtering products by category
     */
    public function test_can_filter_products_by_category(): void
    {
        $category = Category::factory()->create(['name' => 'Indoor Plants']);
        $otherCategory = Category::factory()->create(['name' => 'Outdoor Plants']);

        Product::factory()->count(5)->create(['category_id' => $category->id]);
        Product::factory()->count(3)->create(['category_id' => $otherCategory->id]);

        $response = $this->getJson('/api/products?category=' . $category->id);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertEquals(5, count($response->json('data')));
    }

    /**
     * Test filtering products by price range
     */
    public function test_can_filter_products_by_price_range(): void
    {
        Product::factory()->create(['price' => 10.00]);
        Product::factory()->create(['price' => 50.00]);
        Product::factory()->create(['price' => 100.00]);

        $response = $this->getJson('/api/products?max_price=60');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        foreach ($response->json('data') as $product) {
            $this->assertLessThanOrEqual(60, $product['price']);
        }
    }

    /**
     * Test searching products by name
     */
    public function test_can_search_products_by_name(): void
    {
        Product::factory()->create(['name' => 'Monstera Deliciosa']);
        Product::factory()->create(['name' => 'Snake Plant']);
        Product::factory()->create(['name' => 'Pothos']);

        $response = $this->getJson('/api/products?search=Monstera');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertGreaterThan(0, count($response->json('data')));
        $this->assertStringContainsString('Monstera', $response->json('data.0.name'));
    }

    /**
     * Test showing a single product
     */
    public function test_can_show_single_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->getJson('/api/products/' . $product->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'name',
                    'description',
                    'price',
                    'stock',
                    'category',
                    'images',
                ]
            ])
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $product->id,
                    'name' => $product->name,
                ]
            ]);
    }

    /**
     * Test showing non-existent product returns 404
     */
    public function test_showing_non_existent_product_returns_404(): void
    {
        $response = $this->getJson('/api/products/99999');

        $response->assertStatus(404);
    }

    /**
     * Test product images are formatted correctly
     */
    public function test_product_images_are_formatted(): void
    {
        $product = Product::factory()->create([
            'images' => json_encode(['image1.jpg', 'image2.jpg'])
        ]);

        $response = $this->getJson('/api/products/' . $product->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['images']
            ]);

        $this->assertIsArray($response->json('data.images'));
    }

    /**
     * Test only active products are returned
     */
    public function test_only_active_products_are_listed(): void
    {
        Product::factory()->count(5)->create(['is_active' => true]);
        Product::factory()->count(3)->create(['is_active' => false]);

        $response = $this->getJson('/api/products');

        $response->assertStatus(200);

        foreach ($response->json('data') as $product) {
            $this->assertTrue($product['is_active']);
        }
    }

    /**
     * Test related products endpoint
     */
    public function test_can_get_related_products(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        Product::factory()->count(5)->create(['category_id' => $category->id]);

        $response = $this->getJson('/api/products/' . $product->id . '/related');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        // Should not include the original product
        foreach ($response->json('data') as $relatedProduct) {
            $this->assertNotEquals($product->id, $relatedProduct['id']);
        }
    }
}
