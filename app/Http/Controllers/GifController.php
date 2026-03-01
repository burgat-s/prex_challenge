<?php

namespace App\Http\Controllers;

use App\Contracts\GifProviderInterface;
use App\Http\Requests\SearchGifRequest;
use App\Http\Responses\ApiResponse;
use Exception;

class GifController extends Controller
{
    public function __construct(
        private readonly GifProviderInterface $gifProvider
    ) {}

    public function search(SearchGifRequest $request)
    {
        try {
            $data = $this->gifProvider->search(
                $request->validated('query'),
                $request->validated('limit', 25), 
                $request->validated('offset', 0)
            );
            return ApiResponse::success($data, 'Búsqueda exitosa');
        } catch (Exception $e) {
            return ApiResponse::error('Error al consultar el proveedor de GIFs', 500, $e->getMessage());
        }
    }

    public function findById(string $id)
    {
        try {
            $data = $this->gifProvider->findById($id);
            return ApiResponse::success($data, 'GIF encontrado');
        } catch (Exception $e) {
            return ApiResponse::error('GIF no encontrado', 404, $e->getMessage());
        }
    }
}