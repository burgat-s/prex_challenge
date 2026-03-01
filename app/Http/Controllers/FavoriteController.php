<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFavoriteRequest;
use App\Http\Responses\ApiResponse;
use App\Models\Favorite;
use Illuminate\Support\Facades\DB;
use Exception;

class FavoriteController extends Controller
{
    public function store(StoreFavoriteRequest $request)
    {
        $authenticatedUserId = $request->user()->id;
        $payloadUserId = (int) $request->validated('user_id');

        if ($authenticatedUserId !== $payloadUserId) {
            return ApiResponse::error(
                'Acceso denegado. No puedes guardar favoritos para otro usuario.', 
                403
            );
        }

        try {
            $favorite = DB::transaction(function () use ($request, $payloadUserId) {
                return Favorite::updateOrCreate(
                    [
                        'user_id'  => $payloadUserId,
                        'gif_id'   => $request->validated('gif_id'),
                        'provider' => 'giphy',
                    ],
                    [
                        'alias'    => $request->validated('alias'),
                    ]
                );
            });

            return ApiResponse::success($favorite, 'GIF guardado en favoritos exitosamente', 201);
            
        } catch (Exception $e) {
            return ApiResponse::error('Error interno al guardar el favorito', 500, $e->getMessage());
        }
    }
}