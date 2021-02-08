<?php declare(strict_types=1);

namespace Tests\Feature\Controller\Web\User\Rsi\CommLink;

use App\Models\Account\User\User;
use App\Models\Account\User\UserGroup;

/**
 * Class Comm-Link Controller Test
 *
 * @covers \App\Policies\Web\User\Rsi\CommLink\CommLinkPolicy<extended>
 *
 * @covers \App\Http\Middleware\CheckUserState
 *
 * @covers \App\Providers\RouteServiceProvider
 *
 * @covers \App\Models\Rsi\CommLink\CommLink
 */
class CommLinkControllerSichterTest extends CommLinkControllerTestCase
{
    protected const RESPONSE_STATUSES = [
        'index' => \Illuminate\Http\Response::HTTP_OK,

        'show' => \Illuminate\Http\Response::HTTP_OK,

        'edit' => \Illuminate\Http\Response::HTTP_FORBIDDEN,

        'update' => \Illuminate\Http\Response::HTTP_FORBIDDEN,
        'update_version' => \Illuminate\Http\Response::HTTP_FORBIDDEN,

        'preview' => \Illuminate\Http\Response::HTTP_FORBIDDEN,
    ];

    /**
     * {@inheritdoc}
     * Adds the specific group to the Admin model
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->user->groups()->sync(UserGroup::where('name', 'sichter')->first()->id);
    }
}
