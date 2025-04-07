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
   * List all registered approved songs
   */
  public function index()
  {
    $musics = Music::where('approved', true)
      ->orderBy('views', 'desc')
      ->paginate(5);

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

  // === ADMIN ===
  /**
   * Store a newly created resource in storage by admin.
   */
  public function storeAdm(MusicsRequest $request)
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
      'approved' => true,
    ]);

    return response()->json([
      'status' => Response::HTTP_CREATED,
      'message' => 'Music created successfully',
      'music' => new MusicResource($music),
    ], Response::HTTP_CREATED);
  }

  /**
   * List all pending registered songs
   */
  public function musicsApproval()
  {
    $musics = Music::where('approved', false)
      ->orderBy('views', 'desc')
      ->paginate(5);

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
      'message' => 'No musics to approve',
    ], Response::HTTP_OK);
  }

  /**
   * Approves the specified music in the storage.
   */
  public function approval($id)
  {
    $music = Music::find($id);

    if (!$music) {
      return response()->json([
        'status' => Response::HTTP_BAD_REQUEST,
        'message' => 'Error approving music'
      ], Response::HTTP_BAD_REQUEST);
    }

    $music->approved = true;
    $music->save();

    return response()->json([
      'status' => Response::HTTP_OK,
      'message' => 'Music approved successfully!',
      'music' => $music
    ], Response::HTTP_OK);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(MusicsRequest $request, Music $music)
  {
    $data = $request->validated();

    // Se foi enviado um novo link do YouTube, atualiza os dados automaticamente
    if (!empty($data['youtube_url'])) {
      $videoId = $this->youtube->extractVideoId($data['youtube_url']);

      if (!$videoId) {
        return response()->json([
          'status' => Response::HTTP_BAD_REQUEST,
          'message' => 'Invalid video URL.',
        ], Response::HTTP_BAD_REQUEST);
      }

      try {
        $videoInfo = $this->youtube->getVideoInfo($videoId);
      } catch (\Exception $e) {
        return response()->json([
          'status' => Response::HTTP_BAD_REQUEST,
          'message' => 'Error fetching video information: ' . $e->getMessage(),
        ], Response::HTTP_BAD_REQUEST);
      }

      // Atualiza os campos relacionados ao vídeo
      $data['youtube_id'] = $videoInfo['youtube_id'];
      $data['title'] = $videoInfo['titulo'];
      $data['views'] = $videoInfo['visualizacoes'];
      $data['thumb'] = $videoInfo['thumb'];
    }

    if ($music->update($data)) {
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
  public function destroy($id)
  {
    $music = Music::find($id);

    if (!$music) {
      return response()->json([
        'status' => Response::HTTP_NOT_FOUND,
        'message' => 'Music not found'
      ], Response::HTTP_NOT_FOUND);
    }

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
