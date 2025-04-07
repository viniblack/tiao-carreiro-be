<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class UserControllerTest extends TestCase
{
  use RefreshDatabase;

  #[Test]
  public function it_allows_valid_registration()
  {
    $response = $this->postJson('/api/register', [
      'name' => 'Valid User',
      'email' => 'valid@email.com',
      'role' => 'member',
      'password' => 'secret123',
      'password_confirmation' => 'secret123',
    ]);

    $response->assertStatus(Response::HTTP_CREATED)
      ->assertJsonStructure([
        'token',
        'user' => [
          'id',
          'name',
          'email',
          'role',
          'created_at',
          'updated_at'
        ]
      ]);
  }

  #[Test]
  public function registers_user_successfully()
  {
    $response = $this->postJson('/api/register', [
      'name' => 'Test User',
      'email' => 'test@example.com',
      'password' => 'password',
      'password_confirmation' => 'password',
      'role' => 'member',
    ]);

    $response->assertStatus(Response::HTTP_CREATED)
      ->assertJsonFragment(['message' => 'User created successfully']);

    $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
  }

  #[Test]
  public function login_successful()
  {
    User::factory()->create([
      'email' => 'login@example.com',
      'password' => bcrypt('password123'),
    ]);

    $response = $this->postJson('/api/login', [
      'email' => 'login@example.com',
      'password' => 'password123',
    ]);

    $response->assertStatus(Response::HTTP_OK)
      ->assertJsonFragment(['message' => 'User Token']);
  }

  #[Test]
  public function login_fails_with_wrong_password()
  {
    User::factory()->create([
      'email' => 'wrongpass@example.com',
      'password' => bcrypt('correctpass'),
    ]);

    $response = $this->postJson('/api/login', [
      'email' => 'wrongpass@example.com',
      'password' => 'wrongpass',
    ]);

    $response->assertStatus(Response::HTTP_UNAUTHORIZED)
      ->assertJsonFragment(['message' => 'Not Authorized']);
  }

  #[Test]
  public function logout_works_correctly()
  {
    Sanctum::actingAs(User::factory()->create());

    $response = $this->postJson('/api/logout');

    $response->assertStatus(Response::HTTP_OK)
      ->assertJsonFragment(['message' => 'Logout successful']);
  }

  #[Test]
  public function get_authenticated_user_info()
  {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/user');

    $response->assertStatus(Response::HTTP_OK)
      ->assertJsonFragment(['message' => 'User information'])
      ->assertJsonFragment(['email' => $user->email]);
  }
}
