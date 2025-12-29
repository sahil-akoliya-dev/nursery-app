<?php

namespace Tests\Feature\Feature\Api;

use App\Models\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeatureApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test listing all active features
     */
    public function test_can_list_active_features(): void
    {
        // Create active and inactive features
        Feature::factory()->count(3)->create(['is_active' => true]);
        Feature::factory()->count(2)->create(['is_active' => false]);

        $response = $this->getJson('/api/features');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id',
                        'icon',
                        'title',
                        'description',
                        'color',
                        'order',
                        'is_active',
                    ]
                ]
            ])
            ->assertJson(['success' => true]);

        // Should only return active features
        $this->assertEquals(3, count($response->json('data')));
    }

    /**
     * Test features are ordered correctly
     */
    public function test_features_are_ordered_by_order_field(): void
    {
        Feature::factory()->create(['order' => 3, 'is_active' => true]);
        Feature::factory()->create(['order' => 1, 'is_active' => true]);
        Feature::factory()->create(['order' => 2, 'is_active' => true]);

        $response = $this->getJson('/api/features');

        $response->assertStatus(200);

        $features = $response->json('data');
        $this->assertEquals(1, $features[0]['order']);
        $this->assertEquals(2, $features[1]['order']);
        $this->assertEquals(3, $features[2]['order']);
    }

    /**
     * Test features have all required fields
     */
    public function test_features_have_required_fields(): void
    {
        Feature::factory()->create([
            'icon' => 'sprout',
            'title' => 'Test Feature',
            'description' => 'Test Description',
            'color' => 'green',
            'is_active' => true,
        ]);

        $response = $this->getJson('/api/features');

        $response->assertStatus(200);

        $feature = $response->json('data.0');
        $this->assertArrayHasKey('icon', $feature);
        $this->assertArrayHasKey('title', $feature);
        $this->assertArrayHasKey('description', $feature);
        $this->assertArrayHasKey('color', $feature);
    }
}
