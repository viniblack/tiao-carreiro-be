<?php

namespace Tests\Unit\Requests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MusicRequestTest extends TestCase
{
  use RefreshDatabase;
  
  protected $admin;

  protected function setUp(): void
  {
    parent::setUp();

    $this->admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($this->admin, 'sanctum');
  }

  #[Test]
  public function it_accepts_valid_youtube_url()
  {
    $response = $this->postJson('/api/admin/musics', [
      'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
    ]);

    $response->assertStatus(Response::HTTP_CREATED);
  }

  #[Test]
  public function it_rejects_missing_youtube_url()
  {
    $response = $this->postJson('/api/admin/musics', []);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    $response->assertJsonValidationErrors(['youtube_url']);
  }

  #[Test]
  public function it_rejects_invalid_youtube_url()
  {
    $response = $this->postJson('/api/admin/musics', [
      'youtube_url' => 'not-a-valid-url',
    ]);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    $response->assertJsonValidationErrors(['youtube_url']);
  }

  #[Test]
  public function it_returns_error_for_invalid_youtube_url()
  {
    $this->mock(\App\Services\YouTubeService::class, function ($mock) {
      $mock->shouldReceive('extractVideoId')->andReturn(null);
    });

    $response = $this->postJson('/api/musics', [
      'youtube_url' => 'not-a-valid-url'
    ]);

    $response->assertStatus(422)
      ->assertJsonFragment(["message" => "The 'YouTube URL' must be a valid URL."]);
  }
}
