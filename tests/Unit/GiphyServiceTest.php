<?php

namespace Tests\Unit;

use App\Services\GiphyService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;
use Exception;

class GiphyServiceTest extends TestCase
{
    protected GiphyService $giphyService;

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('services.giphy.api_key', 'test_fake_api_key');
        Config::set('services.giphy.base_url', 'https://api.giphy.com/v1');

        $this->giphyService = new GiphyService();
    }

    public function test_search_returns_mapped_gifs_on_success(): void
    {
        Http::fake([
            '*search*' => Http::response([
                'data' => [
                    [
                        'id' => 'xT4uQul',
                        'title' => 'Homero Simpson',
                        'images' => [
                            'original' => ['url' => 'https://giphy.com/homero.gif']
                        ]
                    ]
                ],
                'pagination' => ['total_count' => 1]
            ], 200)
        ]);

        $resultado = $this->giphyService->search('homero', 1, 0);

        $gif = $resultado['data'][0] ?? $resultado[0] ?? null;

        $this->assertNotNull($gif, 'El array vino vacío o con otra estructura.');
        $this->assertEquals('xT4uQul', $gif['id']);
        $this->assertEquals('Homero Simpson', $gif['title']);
    }

    public function test_find_by_id_returns_single_gif_data(): void
    {
        Http::fake([
            '*gifs/xT4uQul*' => Http::response([
                'data' => [
                    'id' => 'xT4uQul',
                    'title' => 'Homero Simpson',
                    'images' => [
                        'original' => ['url' => 'https://giphy.com/homero.gif']
                    ]
                ]
            ], 200)
        ]);

        $resultado = $this->giphyService->findById('xT4uQul');

        $gif = $resultado['data'] ?? $resultado ?? null;

        $this->assertNotNull($gif, 'El array vino vacío o con otra estructura.');
        $this->assertEquals('xT4uQul', $gif['id']);
        $this->assertEquals('Homero Simpson', $gif['title']);
    }

    public function test_search_throws_exception_when_giphy_api_fails(): void
    {
        Http::fake([
            '*' => Http::response([
                'message' => 'API Rate Limit Exceeded'
            ], 429)
        ]);

        $this->expectException(Exception::class);
        
        $this->giphyService->search('homero');
    }
}