<?php

namespace Tests\Unit\Models;

use App\Models\Music;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MusicModelTest extends TestCase
{
  use RefreshDatabase;

  #[Test]
  public function music_table_has_expected_columns()
  {
    $this->assertTrue(Schema::hasColumns('musics', [
      'id',
      'title',
      'views',
      'youtube_id',
      'thumb',
      'approved',
    ]));
  }

  #[Test]
  public function can_create_music_with_fillable_attributes()
  {
    $data = [
      'title' => 'Tristeza do Jeca',
      'views' => 12345,
      'youtube_id' => 'abc123',
      'thumb' => 'http://imagem.com/thumb.jpg',
      'approved' => true,
    ];

    $music = Music::create($data);

    $this->assertDatabaseHas('musics', ['title' => 'Tristeza do Jeca']);
    $this->assertEquals('abc123', $music->youtube_id);
    $this->assertTrue($music->approved);
  }

  #[Test]
  public function factory_creates_valid_music()
  {
    $music = Music::factory()->create();

    $this->assertInstanceOf(Music::class, $music);
    $this->assertDatabaseHas('musics', ['id' => $music->id]);
  }

  #[Test]
  public function factory_custom_data_works()
  {
    $music = Music::factory()->customData()->create();

    $this->assertNotNull($music->title);
    $this->assertNotEquals(0, $music->views);
    $this->assertNotNull($music->youtube_id);
    $this->assertNotNull($music->thumb);
    $this->assertTrue($music->approved);
    $this->assertDatabaseHas('musics', ['id' => $music->id]);
  }

  #[Test]
  public function seeder_creates_defined_musics()
  {
    $this->seed();

    $this->assertDatabaseCount('musics', 4);
    $this->assertDatabaseHas('musics', [
      'title' => 'Rio de Lágrimas',
      'youtube_id' => 'FxXXvPL3JIg'
    ]);
  }
}
