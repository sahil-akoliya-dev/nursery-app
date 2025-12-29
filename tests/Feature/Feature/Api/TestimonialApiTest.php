<?php

namespace Tests\Feature\Feature\Api;

use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestimonialApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test listing all active testimonials
     */
    public function test_can_list_active_testimonials(): void
    {
        // Create active and inactive testimonials
        Testimonial::factory()->count(3)->create(['is_active' => true]);
        Testimonial::factory()->count(2)->create(['is_active' => false]);

        $response = $this->getJson('/api/testimonials');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'role',
                        'content',
                        'image',
                        'rating',
                        'order',
                        'is_active',
                    ]
                ]
            ])
            ->assertJson(['success' => true]);

        // Should only return active testimonials
        $this->assertEquals(3, count($response->json('data')));
    }

    /**
     * Test testimonials are ordered correctly
     */
    public function test_testimonials_are_ordered_by_order_field(): void
    {
        Testimonial::factory()->create(['order' => 3, 'is_active' => true]);
        Testimonial::factory()->create(['order' => 1, 'is_active' => true]);
        Testimonial::factory()->create(['order' => 2, 'is_active' => true]);

        $response = $this->getJson('/api/testimonials');

        $response->assertStatus(200);

        $testimonials = $response->json('data');
        $this->assertEquals(1, $testimonials[0]['order']);
        $this->assertEquals(2, $testimonials[1]['order']);
        $this->assertEquals(3, $testimonials[2]['order']);
    }

    /**
     * Test testimonials have valid ratings
     */
    public function test_testimonials_have_valid_ratings(): void
    {
        Testimonial::factory()->create(['rating' => 5, 'is_active' => true]);
        Testimonial::factory()->create(['rating' => 4, 'is_active' => true]);

        $response = $this->getJson('/api/testimonials');

        $response->assertStatus(200);

        foreach ($response->json('data') as $testimonial) {
            $this->assertGreaterThanOrEqual(1, $testimonial['rating']);
            $this->assertLessThanOrEqual(5, $testimonial['rating']);
        }
    }
}
