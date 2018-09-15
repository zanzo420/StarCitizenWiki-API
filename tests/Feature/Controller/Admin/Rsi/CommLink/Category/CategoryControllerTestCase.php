<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 07.08.2018
 * Time: 11:52
 */

namespace Tests\Feature\Controller\Admin\Rsi\CommLink\Category;

use App\Models\Rsi\CommLink\Category\Category;
use App\Models\Rsi\CommLink\CommLink;
use App\Models\Rsi\CommLink\CommLinkTranslation;
use Dingo\Api\Http\Response;
use Tests\Feature\Controller\Admin\AdminTestCase;

/**
 * Class Category Controller Test Case
 */
class CategoryControllerTestCase extends AdminTestCase
{
    /**
     * @var \App\Models\Rsi\CommLink\Category\Category
     */
    protected $category;

    /**
     * @var \Illuminate\Database\Eloquent\Collection
     */
    protected $commLinks;

    /**
     * @covers \App\Http\Controllers\Web\Admin\Rsi\CommLink\Category\CategoryController::index
     */
    public function testIndex()
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('web.admin.rsi.comm-links.categories.index'));

        $response->assertStatus(static::RESPONSE_STATUSES['index']);
        if ($response->status() === Response::HTTP_OK) {
            $response->assertViewIs('admin.rsi.comm_links.categories.index')->assertSee($this->category->name);
        }
    }

    /**
     * @covers \App\Http\Controllers\Web\Admin\Rsi\CommLink\Category\CategoryController::show
     * @covers \App\Models\Rsi\CommLink\Category\Category
     */
    public function testShow()
    {
        $response = $this->actingAs($this->admin, 'admin')->get(
            route('web.admin.rsi.comm-links.categories.show', $this->category)
        );

        $response->assertStatus(static::RESPONSE_STATUSES['show']);
        if ($response->status() === Response::HTTP_OK) {
            $response->assertViewIs('admin.rsi.comm_links.index')->assertSee(
                $this->commLinks->first()->title
            );
        }
    }

    /**
     * {@inheritdoc}
     * Creates needed Comm Link Category
     */
    protected function setUp()
    {
        parent::setUp();
        $this->createSystemLanguages();

        $this->category = factory(Category::class)->create();

        $this->commLinks = factory(CommLink::class, 5)->create(['category_id' => $this->category->id])->each(
            function (CommLink $commLink) {
                $commLink->translations()->save(factory(CommLinkTranslation::class)->make());
            }
        );
    }
}
