<?php declare(strict_types = 1);

namespace Tests\Feature\Controller\Api\V1\StarCitizen;

use App\Models\Api\StarCitizen\Stat\Stat;
use Tests\Feature\Controller\Api\ApiTestCase as ApiTestCase;

/**
 * {@inheritdoc}
 *
 * @covers \App\Http\Controllers\Api\V1\StarCitizen\Stat\StatController<extended>
 * @covers \App\Transformers\Api\V1\StarCitizen\Stat\StatTransformer<extended>
 * @covers \App\Models\Api\StarCitizen\Stat\Stat<extended>
 */
class StatControllerTest extends ApiTestCase
{
    /**
     * @var array Default Transformer Structure
     */
    protected $structure = [
        'funds',
        'fans',
        'fleet',
        'timestamp',
    ];

    /**
     * Tests Stats from Interfaces
     *
     * @covers \App\Http\Controllers\Api\V1\StarCitizen\Stat\StatController::index
     */
    public function testIndexPaginatedDefault()
    {
        $response = $this->get('/api/stats');
        $response->assertOk()
            ->assertSee('data')
            ->assertJsonCount(10, 'data')
            ->assertSee('funds')
            ->assertSee('fleet')
            ->assertSee('fans')
            ->assertSee('timestamp')
            ->assertSee('meta')
            ->assertJsonStructure(
                [
                    'data' => [
                        $this->structure,
                    ],
                ]
            );
    }

    /**
     * Tests Stats from Interfaces
     *
     * @covers \App\Http\Controllers\Api\V1\StarCitizen\Stat\StatController::index
     */
    public function testIndexPaginatedCustom()
    {
        $response = $this->get('/api/stats?limit=5');
        $response->assertOk()
            ->assertSee('data')
            ->assertJsonCount(5, 'data')
            ->assertSee('funds')
            ->assertSee('fleet')
            ->assertSee('fans')
            ->assertSee('timestamp')
            ->assertSee('meta')
            ->assertJsonStructure(
                [
                    'data' => [
                        $this->structure,
                    ],
                ]
            );
    }

    /**
     * Tests Stats from Interfaces
     *
     * @covers \App\Http\Controllers\Api\V1\StarCitizen\Stat\StatController::index
     */
    public function testIndexAll()
    {
        $response = $this->get('/api/stats?limit=0');
        $response->assertOk()
            ->assertSee('data')
            ->assertJsonCount(Stat::count(), 'data')
            ->assertSee('funds')
            ->assertSee('fleet')
            ->assertSee('fans')
            ->assertSee('timestamp')
            ->assertJsonStructure(
                [
                    'data' => [
                        $this->structure,
                    ],
                ]
            );
    }

    /**
     * Tests Stats from Interfaces
     *
     * @covers \App\Http\Controllers\Api\V1\StarCitizen\Stat\StatController::index
     */
    public function testIndexInvalidLimit()
    {
        $response = $this->get('/api/stats?limit=-1');
        $response->assertOk()
            ->assertSee('error')
            ->assertJsonStructure(
                [
                    'meta' => [
                        'errors' => [
                            'limit',
                        ],
                    ],
                ]
            )
            ->assertJsonStructure(
                [
                    'data' => [
                        $this->structure,
                    ],
                ]
            );
    }

    /**
     * Tests Stats from Interfaces
     *
     * @covers \App\Http\Controllers\Api\V1\StarCitizen\Stat\StatController::latest
     */
    public function testLatestApiView()
    {
        $response = $this->get('/api/stats/latest');
        $response->assertOk()
            ->assertSee('data')
            ->assertSee('funds')
            ->assertSee('fleet')
            ->assertSee('fans')
            ->assertSee('timestamp')
            ->assertJsonCount(4, 'data')
            ->assertJsonStructure(
                [
                    'data' => $this->structure,
                ]
            );
    }

    /**
     * Creates Faked Stats in DB
     */
    protected function setUp()
    {
        parent::setUp();

        factory(Stat::class, 20)->create();
    }
}
