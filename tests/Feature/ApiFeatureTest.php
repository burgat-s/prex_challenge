<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ApiFeatureTest extends TestCase
{
    use RefreshDatabase; 

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('passport:client', ['--personal' => true, '--name' => 'Test Client']);
    }

    public function test_login_returns_token_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@prex.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@prex.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200) 
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => ['token']
                 ]);
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'no-existe@prex.com',
            'password' => 'falso',
        ]);

        $response->assertStatus(401)
                 ->assertJsonPath('success', false)
                 ->assertJsonPath('message', 'Credenciales inválidas');
    }

    public function test_authenticated_user_can_store_favorite_gif(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $response = $this->postJson('/api/favorites', [
            'gif_id' => 'xT4uQulxzV39haB8WE',
            'alias' => 'Mi GIF de Homero',
            'user_id' => $user->id,
        ]);

        $response->assertStatus(201)
                 ->assertJsonPath('success', true);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'gif_id' => 'xT4uQulxzV39haB8WE',
            'alias' => 'Mi GIF de Homero',
            'provider' => 'giphy',
        ]);
    }
}