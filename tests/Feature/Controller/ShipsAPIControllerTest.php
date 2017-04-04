<?php

namespace Tests\Feature\Controller;

use App\Http\Controllers\StarCitizenWiki\ShipsAPIController;
use App\Repositories\StarCitizenWiki\APIv1\Ships\ShipsRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class ShipsTest
 * @package Tests\Feature
 */
class ShipsAPIControllerTest extends TestCase
{
    /**
     * Get Ship from API
     *
     * @covers \App\Http\Controllers\StarCitizenWiki\ShipsAPIController::getShip()
     * @covers \App\Http\Middleware\ThrottleAPI
     * @covers \App\Http\Middleware\AddAPIHeaders
     * @covers \App\Http\Middleware\PiwikTracking
     * @covers \App\Http\Middleware\UpdateTokenTimestamp
     * @covers \App\Transformers\StarCitizenWiki\Ships\ShipsTransformer
     */
    public function testApiShipView()
    {
        $response = $this->get('api/v1/ships/300i');
        $response->assertSee('300i');
        $response->assertStatus(200);
    }

    /**
     * Test Search
     *
     * @covers \App\Http\Controllers\StarCitizenWiki\ShipsAPIController::searchShips()
     * @covers \App\Http\Middleware\ThrottleAPI
     * @covers \App\Http\Middleware\AddAPIHeaders
     * @covers \App\Http\Middleware\PiwikTracking
     * @covers \App\Http\Middleware\UpdateTokenTimestamp
     * @covers \App\Transformers\StarCitizenWiki\Ships\ShipsSearchTransformer
     */
    public function testSearch()
    {
        $response = $this->post('api/v1/ships/search', ['query' => '300i']);
        $response->assertSee('300i');
        $response->assertStatus(200);
    }

    /**
     * @covers \App\Http\Controllers\StarCitizenWiki\ShipsAPIController::getShipList()
     * @covers \App\Http\Middleware\ThrottleAPI
     * @covers \App\Http\Middleware\AddAPIHeaders
     * @covers \App\Http\Middleware\PiwikTracking
     * @covers \App\Http\Middleware\UpdateTokenTimestamp
     * @covers \App\Transformers\StarCitizenWiki\Ships\ShipsListTransformer
     */
    public function testShipsList()
    {
        $response = $this->get('api/v1/ships/list');
        $response->assertSee('300i');
        $response->assertStatus(200);
    }
}