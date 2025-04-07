<?php

namespace Tests\Feature\Controllers;

use App\Models\Music;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MusicControllerTest extends TestCase
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
  public function it_returns_paginated_approved_musics()
  {
    Music::factory()->count(6)->create(['approved' => true]);
    Music::factory()->count(2)->create(['approved' => false]);

    $response = $this->getJson('/api/musics');

    $response->assertStatus(Response::HTTP_OK)
      ->assertJsonFragment(['message' => 'Music list'])
      ->assertJsonStructure(['pagination', 'musics']);
  }

  #[Test]
  public function it_returns_empty_message_when_no_music_found()
  {
    $response = $this->getJson('/api/musics');

    $response->assertStatus(Response::HTTP_OK)
      ->assertJsonFragment(['message' => 'No music found']);
  }

  #[Test]
  public function it_stores_music_successfully()
  {
    $this->mock(\App\Services\YouTubeService::class, function ($mock) {
      $mock->shouldReceive('extractVideoId')->andReturn('mockedVideoId');
      $mock->shouldReceive('getVideoInfo')->andReturn([
        'youtube_id' => 'mockedVideoId',
        'titulo' => 'Mock Title',
        'visualizacoes' => 12345,
        'thumb' => 'mockedThumb.jpg',
      ]);
    });

    $response = $this->postJson('/api/musics', [
      'youtube_url' => 'https://youtube.com/watch?v=mockedVideoId',
    ]);

    $response->assertStatus(Response::HTTP_CREATED)
      ->assertJsonFragment(['message' => 'Music created successfully']);
    $this->assertDatabaseHas('musics', ['youtube_id' => 'mockedVideoId']);
  }

  #[Test]
  public function it_shows_music()
  {
    $music = Music::factory()->create();

    $response = $this->getJson("/api/musics/{$music->id}");

    $response->assertStatus(Response::HTTP_OK)
      ->assertJsonFragment(['message' => 'Music found']);
  }

  #[Test]
  public function it_returns_not_found_when_music_does_not_exist()
  {
    $response = $this->getJson("/api/musics/999");

    $response->assertStatus(Response::HTTP_NOT_FOUND)
      ->assertJsonFragment(['message' => 'Music not found']);
  }

  #[Test]
  public function it_approves_pending_music()
  {
    $music = Music::factory()->create(['approved' => false]);

    $response = $this->postJson("/api/admin/approve/{$music->id}");

    $response->assertStatus(Response::HTTP_OK)
      ->assertJsonFragment(['message' => 'Music approved successfully!']);

    $this->assertEquals(1, $music->fresh()->approved);
  }

  #[Test]
  public function it_returns_error_when_approving_non_existing_music()
  {
    $response = $this->postJson('/api/admin/approve/999');

    $response->assertStatus(Response::HTTP_BAD_REQUEST)
      ->assertJsonFragment(['message' => 'Error approving music']);
  }

  #[Test]
  public function it_returns_pending_musics()
  {
    Music::factory()->count(4)->create(['approved' => false]);

    $response = $this->getJson('/api/admin/pending');

    $response->assertStatus(Response::HTTP_OK)
      ->assertJsonFragment(['message' => 'Music list'])
      ->assertJsonStructure(['pagination', 'musics']);
  }

  #[Test]
  public function it_deletes_music_successfully()
  {
    $music = Music::factory()->create();

    $response = $this->deleteJson("/api/admin/rejecting/{$music->id}");

    $response->assertStatus(Response::HTTP_OK)
      ->assertJsonFragment(['message' => 'Deleted music']);
    $this->assertDatabaseMissing('musics', ['id' => $music->id]);
  }

  #[Test]
  public function it_returns_error_when_deleting_non_existing_music()
  {
    $response = $this->deleteJson("/api/admin/rejecting/999");

    $response->assertStatus(Response::HTTP_NOT_FOUND)
      ->assertJsonFragment(['message' => 'Music not found']);
  }
}
