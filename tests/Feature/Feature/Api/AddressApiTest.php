<?php

namespace Tests\Feature\Feature\Api;

use App\Models\Address;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddressApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_list_own_addresses(): void
    {
        $user = User::factory()->create();
        Address::factory()->count(3)->create(['user_id' => $user->id]);
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/addresses');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => ['id', 'label', 'address_line_1', 'city', 'state']
                ]
            ]);

        $this->assertEquals(3, count($response->json('data')));
    }

    public function test_user_can_create_address(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/addresses', [
                'label' => 'Home',
                'address_line_1' => '123 Main St',
                'city' => 'New York',
                'state' => 'NY',
                'postal_code' => '10001',
                'country' => 'USA'
            ]);

        $response->assertStatus(201)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('addresses', [
            'user_id' => $user->id,
            'label' => 'Home'
        ]);
    }

    public function test_user_can_update_own_address(): void
    {
        $user = User::factory()->create();
        $address = Address::factory()->create(['user_id' => $user->id]);
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->putJson('/api/addresses/' . $address->id, [
                'label' => 'Work',
                'address_line_1' => '456 Office Blvd',
                'city' => 'Boston',
                'state' => 'MA',
                'postal_code' => '02101',
                'country' => 'USA'
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('addresses', [
            'id' => $address->id,
            'label' => 'Work'
        ]);
    }

    public function test_user_cannot_update_others_address(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $address = Address::factory()->create(['user_id' => $otherUser->id]);
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->putJson('/api/addresses/' . $address->id, [
                'label' => 'Trying to update'
            ]);

        $response->assertStatus(403);
    }

    public function test_user_can_delete_own_address(): void
    {
        $user = User::factory()->create();
        $address = Address::factory()->create(['user_id' => $user->id]);
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->deleteJson('/api/addresses/' . $address->id);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('addresses', ['id' => $address->id]);
    }

    public function test_address_requires_valid_data(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/addresses', [
                'label' => 'Home'
                // Missing required fields
            ]);

        $response->assertStatus(422);
    }
}
