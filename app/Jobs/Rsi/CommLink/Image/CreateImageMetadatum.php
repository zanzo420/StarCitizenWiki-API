<?php declare(strict_types=1);
/*
 * Copyright (c) 2020
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace App\Jobs\Rsi\CommLink\Image;

use App\Jobs\AbstractBaseDownloadData as BaseDownloadData;
use App\Models\Rsi\CommLink\Image\Image;
use Carbon\Carbon;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\TooManyRedirectsException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateImageMetadatum extends BaseDownloadData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Image $image;

    /**
     * Create a new job instance.
     *
     * @param Image $image
     */
    public function __construct(Image $image)
    {
        $this->image = $image;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->initClient();
        $url = $this->image->url;

        try {
            $response = self::$client->head($url);
        } catch (ServerException | ConnectException | TooManyRedirectsException $e) {
            app('Log')::debug('Header request failed. Retrying in 300 seconds.', [$url, $e->getCode()]);

            $this->release(300);

            return;
        } catch (ClientException $e) {
            app('Log')::info("Header request resulted in code {$e->getCode()}", [$url]);

            $this->image->metadata()->create([
                'mime' => 'undefined',
                'size' => 0,
                'last_modified' => '0001-01-01 00:00:00',
            ]);

            return;
        }

        $data = [
            'mime' => $response->getHeaderLine('content-type'),
            'size' => $response->getHeaderLine('content-length'),
            'last_modified' => Carbon::parse($response->getHeaderLine('last-modified'))->toDateTimeString(),
        ];

        foreach ($data as $key => $datum){
            if ($datum === '') {
                unset($data[$key]);
            }
        }

        $this->image->metadata()->create($data);
    }
}