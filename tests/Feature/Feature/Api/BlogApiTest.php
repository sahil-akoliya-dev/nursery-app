<?php

namespace Tests\Feature\Feature\Api;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_published_posts(): void
    {
        Post::factory()->count(5)->create(['status' => 'published']);
        Post::factory()->count(2)->create(['status' => 'draft']);

        $response = $this->getJson('/api/posts');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => ['id', 'title', 'slug', 'excerpt', 'image', 'published_at']
                ]
            ]);

        $this->assertEquals(5, count($response->json('data')));
    }

    public function test_can_get_latest_posts(): void
    {
        Post::factory()->count(5)->create(['status' => 'published']);

        $response = $this->getJson('/api/posts/latest');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertLessThanOrEqual(3, count($response->json('data')));
    }

    public function test_can_show_post_by_slug(): void
    {
        $post = Post::factory()->create([
            'slug' => 'test-post',
            'status' => 'published'
        ]);

        $response = $this->getJson('/api/posts/test-post');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => ['slug' => 'test-post']
            ]);
    }

    public function test_cannot_show_draft_post(): void
    {
        $post = Post::factory()->create([
            'slug' => 'draft-post',
            'status' => 'draft'
        ]);

        $response = $this->getJson('/api/posts/draft-post');

        $response->assertStatus(404);
    }
}
