<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateTicketTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Új ticket létrehozása helyes bemenetekkel.
     */
    public function testCreateNewTicketWithCorrectInputs(): void
    {
        // Teszt felhasználó készítése
        $user = User::factory()->create([ 'admin' => false ]);
        $token = $user->createToken('auth_token')->plainTextToken;

        // Kérés előkészítése
        $header = [ 'Authorization' => "Bearer $token" ];
        $payload = [
            'title' => fake()->word(),
            'priority' => fake()->numberBetween(0, 3),
            'text' => fake()->paragraph(),
        ];

        // HTTP kérés szimulálása
        $this->json('POST', 'api/tickets', $payload, $header)
            ->assertStatus(201)
            ->assertJsonFragment([
                'title' => $payload['title'],
                'priority' => $payload['priority'],
            ]);

        $this->assertDatabaseHas('tickets', [
            'title' => $payload['title'],
            'done' => false,
            'priority' => $payload['priority'],
        ]);

        $this->assertDatabaseHas('comments', [
            'text' => $payload['text'],
            'filename' => null,
            'filename_hash' => null,
            'user_id' => $user->id,
        ]);
    }
}
