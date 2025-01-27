<?php declare(strict_types=1);

namespace Tests\Feature\Controller\Web\User;

use App\Models\Account\User\UserGroup;
use Illuminate\Http\Response;

/**
 * Class UserControllerTest
 *
 * @covers \App\Policies\Web\User\UserPolicy<extended>
 *
 * @covers \App\Models\Account\User\User
 *
 * @covers \App\Http\Middleware\CheckUserState
 *
 * @covers \App\Providers\RouteServiceProvider
 */
class UserControllerUserTest extends UserControllerTestCase
{
    protected const RESPONSE_STATUSES = [
        'index' => Response::HTTP_FORBIDDEN,

        'edit' => Response::HTTP_FORBIDDEN,
        'edit_not_found' => Response::HTTP_NOT_FOUND,

        'update' => Response::HTTP_FORBIDDEN,
        'update_not_found' => Response::HTTP_NOT_FOUND,

        'block' => Response::HTTP_FORBIDDEN,
    ];

    /**
     * {@inheritdoc}
     * Adds the specific group to the Admin model
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->user->groups()->sync([UserGroup::where('name', 'user')->first()->id]);
    }
}
