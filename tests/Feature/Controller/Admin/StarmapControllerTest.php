<?php

namespace Tests\Feature\Controller\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Class StarmapControllerTest
 * @package Tests\Feature\Controller\Admin
 */
class StarmapControllerTest extends TestCase
{
    use DatabaseTransactions;

    private $user;

    protected function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->user = User::find(1);
    }

    /**
     * @covers \App\Http\Controllers\Auth\Admin\StarmapController::showStarmapSystemsView()
     */
    public function testStarmapSystemsView()
    {
        $response = $this->actingAs($this->user)->get('admin/starmap/systems');
        $response->assertStatus(200);
    }

    /**
     * @covers \App\Http\Controllers\Auth\Admin\StarmapController::showStarmapCelestialObjectView()
     */
    public function testStarmapCelestialObjectsView()
    {
        $response = $this->actingAs($this->user)->get('admin/starmap/celestialobject');
        $response->assertStatus(200);
    }

    /**
     * @covers \App\Http\Controllers\Auth\Admin\StarmapController::updateStarmapSystem()
     */
    public function testUpdateStarmapSystem()
    {
        $response = $this->actingAs($this->user)->patch('admin/starmap/systems', [
            'id' => 1,
            'code' => 'NEWSYSTEM',
        ]);
        $response->assertStatus(302);
    }
}
