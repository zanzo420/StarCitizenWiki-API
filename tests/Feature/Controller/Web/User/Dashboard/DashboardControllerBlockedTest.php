<?php declare(strict_types = 1);

namespace Tests\Feature\Controller\Web\User\Dashboard;

use App\Models\Account\User\User;
use App\Models\Account\User\UserGroup;

/**
 * Class AdminControllerTest
 *
 * @covers \App\Policies\Web\User\DashboardPolicy<extended>
 *
 * @covers \App\Http\Middleware\CheckUserState
 */
class DashboardControllerBlockedTest extends DashboardControllerTestCase
{
    protected const RESPONSE_STATUSES = [
        'show' => \Illuminate\Http\Response::HTTP_FORBIDDEN,
    ];

    /**
     * {@inheritdoc}
     * Adds the specific group to the Admin model
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->state('blocked')->create();
        $this->user->groups()->sync(UserGroup::where('name', 'bureaucrat')->first()->id);
    }
}
