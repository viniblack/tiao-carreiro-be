<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MusicResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    return [
      'id' => $this->id,
      'title' => $this->title,
      'views' => $this->views,
      'youtube_id' => $this->youtube_id,
      'thumb' => $this->thumb,
      'approved' => $this->approved,
      'created_at' => $this->created_at->toDateTimeString(),
    ];
  }
}
