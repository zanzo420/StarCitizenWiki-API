<?php declare(strict_types = 1);

namespace Tests\Feature\Controller\Admin\User;

use App\Models\Account\Admin\Admin;
use App\Models\Account\Admin\AdminGroup;
use Illuminate\Http\Response;

/**
 * Class UserControllerTest
 *
 * {@inheritdoc}
 */
class UserControllerSichterTest extends BaseUserControllerTestCase
{
    protected const RESPONSE_STATUSES = [
        'index' => Response::HTTP_FORBIDDEN,

        'edit' => Response::HTTP_FORBIDDEN,
        'edit_not_found' => Response::HTTP_FORBIDDEN,

        'update' => Response::HTTP_FORBIDDEN,
        'update_not_found' => Response::HTTP_FORBIDDEN,

        'delete' => Response::HTTP_FORBIDDEN,
        'delete_not_found' => Response::HTTP_FORBIDDEN,
    ];

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->admin = factory(Admin::class)->create();
        $this->admin->groups()->sync([AdminGroup::where('name', 'sichter')->first()->id]);
    }
}
