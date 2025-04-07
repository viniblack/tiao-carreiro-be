<?php

namespace Tests\Unit\Resources;

use App\Http\Resources\MusicResource;
use App\Models\Music;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MusicResourceTest extends TestCase
{
  use RefreshDatabase;

  #[Test]
  public function it_returns_the_expected_music_resource_structure()
  {
    $now = Carbon::now();
    Carbon::setTestNow($now);

    $music = Music::factory()->create([
      'title' => 'Tristeza do Jeca',
      'views' => 154000,
      'youtube_id' => 'tRQ2PWlCcZk',
      'thumb' => 'https://img.youtube.com/vi/tRQ2PWlCcZk/hqdefault.jpg',
      'approved' => true,
    ]);

    $resource = (new MusicResource($music))->toArray(request());

    $this->assertEquals([
      'id' => $music->id,
      'title' => 'Tristeza do Jeca',
      'views' => 154000,
      'youtube_id' => 'tRQ2PWlCcZk',
      'thumb' => 'https://img.youtube.com/vi/tRQ2PWlCcZk/hqdefault.jpg',
      'approved' => true,
      'created_at' => $now->toDateTimeString(),
    ], $resource);
  }
}
