<?php

namespace Database\Seeders;

use App\Models\Music;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  public function run(): void
  {
    $musics = [
      [
        'title' => 'Pagode em Brasília',
        'views' => 5000000,
        'youtube_id' => 'lpGGNA6_920',
        'thumb' => 'https://img.youtube.com/vi/lpGGNA6_920/hqdefault.jpg',
        'approved' => true,
      ],
      [
        'title' => 'Rio de Lágrimas',
        'views' => 153000,
        'youtube_id' => 'FxXXvPL3JIg',
        'thumb' => 'https://img.youtube.com/vi/FxXXvPL3JIg/hqdefault.jpg',
        'approved' => true,
      ],
      [
        'title' => 'Tristeza do Jeca',
        'views' => 154000,
        'youtube_id' => 'tRQ2PWlCcZk',
        'thumb' => 'https://img.youtube.com/vi/tRQ2PWlCcZk/hqdefault.jpg',
        'approved' => true,
      ],
      [
        'title' => 'Terra roxa',
        'views' => 3300000,
        'youtube_id' => '4Nb89GFu2g4',
        'thumb' => 'https://img.youtube.com/vi/4Nb89GFu2g4/hqdefault.jpg',
        'approved' => true,
      ],
    ];

    foreach ($musics as $music) {
      Music::create($music);
    }
  }
}
