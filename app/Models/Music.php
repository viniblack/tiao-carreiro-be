<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Music extends Model
{
    /** @use HasFactory<\Database\Factories\MusicFactory> */
    use HasFactory;

    protected $table = 'musics';
    protected $primaryKey = 'id';
    protected $fillable = [
        'title',
        'views',
        'youtube_id',
        'thumb',
        'approved'
    ];
}
