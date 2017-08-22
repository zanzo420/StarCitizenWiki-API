<?php declare(strict_types = 1);

namespace Tests\Feature\Model;

use App\Exceptions\UrlNotWhitelistedException;
use App\Models\ShortUrl\ShortUrl;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Class ShortUrlModelTest
 * @package Tests\Feature\Model
 */
class ShortUrlModelTest extends TestCase
{
    use DatabaseTransactions;

    private $url;
    private $hashName;

    /**
     * Test not WhitelistedException
     *
     * @covers \App\Models\ShortUrl\ShortUrl::createShortUrl()
     * @covers \App\Exceptions\UrlNotWhitelistedException
     */
    public function testNotWhitelistedException()
    {
        $this->expectException(UrlNotWhitelistedException::class);
        ShortUrl::createShortUrl(
            [
                'url' => 'https://notwhitelisted.com',
            ]
        );
    }

    /**
     * Test NotFound Exception
     *
     * @covers \App\Models\ShortUrl\ShortUrl::resolve()
     * @covers \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function testHashNotExistsException()
    {
        $this->expectException(ModelNotFoundException::class);
        ShortUrl::resolve('Does_Not_Exist');
    }

    /**
     * Test Repository Creation
     *
     * @covers \App\Models\ShortUrl\ShortUrl::createShortUrl()
     */
    public function testShortUrlCreation()
    {
        $url = ShortUrl::createShortUrl(
            [
                'url'        => $this->url,
                'hash'  => $this->hashName,
                'user_id'    => 1,
                'expires_at' => null,
            ]
        );

        $this->assertEquals($this->hashName, $url->hash);
    }

    /**
     * @covers \App\Models\ShortUrl\ShortUrl::sanitizeUrl()
     */
    public function testUrlSanitization()
    {
        $url = 'https://star-citizen.wiki';
        $urlSanitized = ShortUrl::sanitizeUrl($url);

        $this->assertEquals($url.'/', $urlSanitized);
    }

    protected function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->url = 'https://star-citizen.wiki/'.str_random(16);
        $this->hashName = str_random(6);
    }
}
