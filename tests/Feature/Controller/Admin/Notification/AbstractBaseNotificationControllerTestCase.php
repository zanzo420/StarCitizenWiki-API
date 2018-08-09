<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 07.08.2018
 * Time: 11:52
 */

namespace Tests\Feature\Controller\Admin\Notification;

use App\Models\Api\Notification;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class AbstractBaseNotificationControllerTest
 */
abstract class AbstractBaseNotificationControllerTestCase extends TestCase
{
    use RefreshDatabase;

    protected const RESPONSE_STATUSES = [];

    /**
     * @var array
     */
    protected $notifications;

    /**
     * @var \App\Models\Account\Admin\Admin
     */
    protected $admin;

    /**
     * @covers \App\Http\Controllers\Web\Admin\Notification\NotificationController::index
     */
    public function testIndex()
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('web.admin.notifications.index'));

        $response->assertStatus(static::RESPONSE_STATUSES['index']);
    }

    /**
     * @covers \App\Http\Controllers\Web\Admin\Notification\NotificationController::create
     */
    public function testCreate()
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('web.admin.notifications.create'));

        $response->assertStatus(static::RESPONSE_STATUSES['create']);
    }


    /**
     * @covers \App\Http\Controllers\Web\Admin\Notification\NotificationController::edit
     */
    public function testEdit()
    {
        $notification = $this->notifications[0];

        $response = $this->actingAs($this->admin, 'admin')->get(route('web.admin.notifications.edit', $notification));

        $response->assertStatus(static::RESPONSE_STATUSES['edit']);
    }

    /**
     * @covers \App\Http\Controllers\Web\Admin\Notification\NotificationController::store
     * @covers \App\Http\Controllers\Web\Admin\Notification\NotificationController::processOutput
     * @covers \App\Http\Controllers\Web\Admin\Notification\NotificationController::processPublishedAt
     */
    public function testStore()
    {
        $response = $this->actingAs($this->admin, 'admin')->post(
            route('web.admin.notifications.store'),
            [
                'content' => str_random(100),
                'level' => rand(0, 3),
                'expired_at' => Carbon::now()->addDay(),
                'output' => [
                    'index',
                ],
            ]
        );

        $response->assertStatus(static::RESPONSE_STATUSES['store']);
    }

    /**
     * @covers \App\Http\Controllers\Web\Admin\Notification\NotificationController::update
     * @covers \App\Http\Controllers\Web\Admin\Notification\NotificationController::processOutput
     * @covers \App\Http\Controllers\Web\Admin\Notification\NotificationController::processPublishedAt
     */
    public function testUpdate()
    {
        /** @var \App\Models\Api\Notification $notification */
        $notification = $this->notifications[1];

        $response = $this->actingAs($this->admin, 'admin')->patch(
            route('web.admin.notifications.update', $notification),
            [
                'content' => str_random(100),
                'level' => rand(0, 3),
                'expired_at' => Carbon::now()->addDay(),
                'published_at' => $notification->published_at,
                'order' => 0,
                'output' => [
                    'index',
                    'status',
                ],
            ]
        );

        $response->assertStatus(static::RESPONSE_STATUSES['update']);
    }

    /**
     * @covers \App\Http\Controllers\Web\Admin\Notification\NotificationController::update
     * @covers \App\Http\Controllers\Web\Admin\Notification\NotificationController::destroy
     */
    public function testDestroy()
    {
        $notification = $this->notifications[2];

        $response = $this->actingAs($this->admin, 'admin')->patch(
            route('web.admin.notifications.update', $notification),
            [
                'delete' => true,
            ]
        );

        $response->assertStatus(static::RESPONSE_STATUSES['destroy']);
    }

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        parent::setUp();
        $this->createAdminGroups();
        $this->notifications = factory(Notification::class, 5)->states('active')->create();
    }
}