<?php

namespace App\Http\Controllers;

use App\Http\Requests\MusicsRequest;
use App\Http\Resources\MusicResource;
use App\Models\Music;
use App\Services\YouTubeService;
use Illuminate\Http\Response;

class MusicController extends Controller
{
  protected $youtube;

  public function __construct(YouTubeService $youtube)
  {
    $this->youtube = $youtube;
  }

  /**
   * List all registered musics
   */
  public function index()
  {
    $musics = Music::paginate(5);

    if (!$musics->isEmpty()) {
      $paginationData = $musics->toArray();

      return response()->json([
        'status' => Response::HTTP_OK,
        'message' => 'Music list',
        'pagination' => [
          'currentPage' => $paginationData['current_page'],
          'totalPages' => $paginationData['last_page'],
          'totalMusics' => $paginationData['total'],
          'perPage' => $paginationData['per_page'],
          'prev_page_url' => $paginationData['prev_page_url'],
          'next_page_url' => $paginationData['next_page_url'],
        ],
        'musics' => MusicResource::collection($musics)
      ],  Response::HTTP_OK);
    }

    return response()->json([
      'status' => Response::HTTP_OK,
      'message' => 'No music found',
    ], Response::HTTP_OK);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(MusicsRequest $request)
  {
    $data = $request->validated();

    // Pega o ID do vídeo a partir da URL enviada
    $videoId = $this->youtube->extractVideoId($data['youtube_url']);

    if (!$videoId) {
      return response()->json([
        'status' => Response::HTTP_BAD_REQUEST,
        'message' => 'Invalid video URL.',
      ], Response::HTTP_BAD_REQUEST);
    }

    try {
      // Pega os dados do vídeo pelo ID
      $videoInfo = $this->youtube->getVideoInfo($videoId);
    } catch (\Exception $e) {
      return response()->json([
        'status' => Response::HTTP_BAD_REQUEST,
        'message' => 'Error fetching video information: ' . $e->getMessage(),
      ], Response::HTTP_BAD_REQUEST);
    }

    // Cria a música no banco
    $music = Music::create([
      'youtube_id' => $videoInfo['youtube_id'],
      'title' => $videoInfo['titulo'],
      'views' => $videoInfo['visualizacoes'],
      'thumb' => $videoInfo['thumb'],
    ]);

    return response()->json([
      'status' => Response::HTTP_CREATED,
      'message' => 'Music created successfully',
      'music' => new MusicResource($music),
    ], Response::HTTP_CREATED);
  }

  /**
   * Display the specified resource.
   */
  public function show($id)
  {
    $music = Music::find($id);

    if (!$music) {
      return response()->json([
        'status' => Response::HTTP_NOT_FOUND,
        'message' => 'Music not found',
      ], Response::HTTP_NOT_FOUND);
    }

    return response()->json([
      'status' => Response::HTTP_OK,
      'message' => 'Music found',
      'music' => new MusicResource($music),
    ], Response::HTTP_OK);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(MusicsRequest $request, Music $music)
  {
    if ($music->update($request->all())) {
      return response()->json([
        'status' => Response::HTTP_OK,
        'message' => 'Music updated successfully',
        'music' => new MusicResource($music),
      ], Response::HTTP_OK);
    }

    return response()->json([
      'status' => Response::HTTP_BAD_REQUEST,
      'message' => 'Error updating music',
    ], Response::HTTP_BAD_REQUEST);
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Music $music)
  {
    if ($music->delete()) {
      return response()->json([
        'status' => Response::HTTP_OK,
        'message' => 'Deleted music'
      ], Response::HTTP_OK);
    }

    return response()->json([
      'status' => Response::HTTP_BAD_REQUEST,
      'message' => 'Error deleting music'
    ], Response::HTTP_BAD_REQUEST);
  }
}
