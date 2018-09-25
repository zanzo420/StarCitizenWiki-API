<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 15.09.2018
 * Time: 18:04
 */

namespace Tests\Feature\Controller\Web\Web\Api;

use App\Models\Rsi\CommLink\CommLink;
use Illuminate\Http\Response;
use Tests\TestCase;

/**
 * Class CommLinkControllerTest
 *
 * @covers \App\Http\Controllers\Web\Api\CommLinkController
 */
class CommLinkControllerTest extends TestCase
{
    /**
     * @var \App\Models\Rsi\CommLink\CommLink
     */
    private $commLink;

    /**
     * @covers \App\Http\Controllers\Web\Api\CommLinkController::show
     */
    public function testShow()
    {
        $response = $this->get(route('web.api.comm-link.show', $this->commLink));
        $response->assertStatus(Response::HTTP_OK)->assertSee(__('Keine deutsche Übersetzung vorhanden.'));
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();
        $this->commLink = factory(CommLink::class)->create();
    }
}