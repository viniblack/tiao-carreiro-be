<?php

namespace App\Services;

use Exception;

class YouTubeService
{
  public function extractVideoId(string $url): ?string
  {
    // Expressões para diferentes formatos
    $patterns = [
      '/youtu\.be\/([^\?&]+)/',
      '/youtube\.com\/watch\?v=([^\?&]+)/',
      '/youtube\.com\/embed\/([^\?&]+)/',
    ];

    foreach ($patterns as $pattern) {
      if (preg_match($pattern, $url, $matches)) {
        return $matches[1];
      }
    }

    return null;
  }

  public function getVideoInfo(string $videoId): array
  {
    $url = "https://www.youtube.com/watch?v=" . $videoId;

    $ch = curl_init();

    curl_setopt_array($ch, [
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_SSL_VERIFYPEER => false,
      CURLOPT_USERAGENT => 'Mozilla/5.0'
    ]);

    $response = curl_exec($ch);

    if ($response === false) {
      throw new Exception("Erro ao acessar o YouTube: " . curl_error($ch));
    }

    curl_close($ch);

    if (!preg_match('/<title>(.+?) - YouTube<\/title>/', $response, $titleMatches)) {
      throw new Exception("Não foi possível encontrar o título do vídeo");
    }

    $title = html_entity_decode($titleMatches[1], ENT_QUOTES);

    if (preg_match('/"viewCount":\s*"(\d+)"/', $response, $viewMatches)) {
      $views = (int)$viewMatches[1];
    } elseif (preg_match('/\"viewCount\"\s*:\s*{.*?\"simpleText\"\s*:\s*\"([\d,\.]+)\"/', $response, $viewMatches)) {
      $views = (int)str_replace(['.', ','], '', $viewMatches[1]);
    } else {
      $views = 0;
    }

    return [
      'titulo' => $title,
      'visualizacoes' => $views,
      'youtube_id' => $videoId,
      'thumb' => 'https://img.youtube.com/vi/' . $videoId . '/hqdefault.jpg',
    ];
  }
}
