<?php

namespace Tests\Unit\Requests;

use Tests\TestCase;
use Illuminate\Http\Response;
use PHPUnit\Framework\Attributes\Test;

class UserRequestTest extends TestCase
{
  #[Test]
  public function it_validates_required_fields()
  {
    $response = $this->postJson('/api/register', []);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
      ->assertJsonValidationErrors(['name', 'email', 'role', 'password']);
  }

  #[Test]
  public function it_validates_email_format_and_uniqueness()
  {
    $this->postJson('/api/register', [
      'name' => 'User Test',
      'email' => 'not-an-email',
      'role' => 'member',
      'password' => 'secret123',
      'password_confirmation' => 'secret123',
    ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
      ->assertJsonValidationErrors(['email']);

    $this->postJson('/api/register', [
      'name' => 'User 1',
      'email' => 'user@email.com',
      'role' => 'member',
      'password' => 'secret123',
      'password_confirmation' => 'secret123',
    ]);

    $this->postJson('/api/register', [
      'name' => 'User 2',
      'email' => 'user@email.com',
      'role' => 'member',
      'password' => 'secret123',
      'password_confirmation' => 'secret123',
    ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
      ->assertJsonValidationErrors(['email']);
  }

  #[Test]
  public function it_requires_password_confirmation()
  {
    $this->postJson('/api/register', [
      'name' => 'User Test',
      'email' => 'test@email.com',
      'role' => 'member',
      'password' => 'secret123',
    ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
      ->assertJsonValidationErrors(['password']);
  }
}
