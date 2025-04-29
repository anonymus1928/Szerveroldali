<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateTicketTest extends TestCase
{
    /**
     * Create new ticket with correct inputs test
     */
    public function test_create_new_ticket_with_correct_inputs(): void
    {
        $user = User::factory()->create([ 'admin' => true ]);
        $token = $user->createToken('auth_token')->plainTextToken;

        $header = [ 'Authorization' => "Bearer $token" ];
        $payload = [
            'title' => fake()->word(),
            'priority' => fake()->numberBetween(0, 3),
            'text' => fake()->paragraph(),
        ];

        $this->json('POST', 'api/tickets', $payload, $header)
             ->assertStatus(201)
             ->assertJsonFragment([
                'title' => $payload['title'],
                'priority' => $payload['priority'],
             ]);

        $this->assertDatabaseHas('tickets', [
            'title' => $payload['title'],
            'priority' => $payload['priority'],
            'done' => false,
        ]);

        $this->assertDatabaseHas('comments', [
            'text' => $payload['text'],
            'filename' => null,
            'filename_hash' => null,
            'user_id' => $user->id,
        ]);
    }
}
