<?php

namespace Tests\Feature\Feature\Api;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_review(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/reviews', [
                'product_id' => $product->id,
                'rating' => 5,
                'comment' => 'Great product!'
            ]);

        $response->assertStatus(201)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('reviews', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'rating' => 5
        ]);
    }

    public function test_unauthenticated_user_cannot_create_review(): void
    {
        $product = Product::factory()->create();

        $response = $this->postJson('/api/reviews', [
            'product_id' => $product->id,
            'rating' => 5,
            'comment' => 'Great product!'
        ]);

        $response->assertStatus(401);
    }

    public function test_can_list_product_reviews(): void
    {
        $product = Product::factory()->create();
        Review::factory()->count(5)->create(['product_id' => $product->id]);

        $response = $this->getJson('/api/products/' . $product->id . '/reviews');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'data' => [
                        '*' => ['id', 'rating', 'comment', 'user']
                    ]
                ]
            ]);
    }

    public function test_user_can_update_own_review(): void
    {
        $user = User::factory()->create();
        $review = Review::factory()->create(['user_id' => $user->id]);
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->putJson('/api/reviews/' . $review->id, [
                'rating' => 4,
                'comment' => 'Updated review'
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('reviews', [
            'id' => $review->id,
            'rating' => 4,
            'comment' => 'Updated review'
        ]);
    }

    public function test_user_cannot_update_others_review(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $review = Review::factory()->create(['user_id' => $otherUser->id]);
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->putJson('/api/reviews/' . $review->id, [
                'rating' => 4,
                'comment' => 'Trying to update'
            ]);

        $response->assertStatus(403);
    }

    public function test_user_can_delete_own_review(): void
    {
        $user = User::factory()->create();
        $review = Review::factory()->create(['user_id' => $user->id]);
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->deleteJson('/api/reviews/' . $review->id);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('reviews', ['id' => $review->id]);
    }

    public function test_review_requires_valid_rating(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/reviews', [
                'product_id' => $product->id,
                'rating' => 6, // Invalid rating
                'comment' => 'Great product!'
            ]);

        $response->assertStatus(422);
    }
}
