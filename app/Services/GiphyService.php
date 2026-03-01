<?php

namespace App\Services;

use App\Contracts\GifProviderInterface;
use Illuminate\Support\Facades\Http;
use Exception;

class GiphyService implements GifProviderInterface
{
    private string $baseUrl;
    private string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.giphy.base_url');
        $this->apiKey = config('services.giphy.api_key');
    }

    public function search(string $query, int $limit = 25, int $offset = 0): array
    {
        $response = Http::get("{$this->baseUrl}/gifs/search", [
            'api_key' => $this->apiKey,
            'q'       => $query,
            'limit'   => $limit,
            'offset'  => $offset,
        ]);

        if ($response->failed()) {
            throw new Exception('Error al comunicarse con la API de GIPHY para la búsqueda.');
        }

        return $response->json();
    }

    public function findById(string $id): array
    {
        $response = Http::get("{$this->baseUrl}/gifs/{$id}", [
            'api_key' => $this->apiKey,
        ]);

        if ($response->failed()) {
            throw new Exception('Error al comunicarse con la API de GIPHY o GIF no encontrado.');
        }

        return $response->json();
    }
}