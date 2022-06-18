<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rsi\CommLink;

use App\Http\Controllers\Api\AbstractApiController as ApiController;
use App\Http\Requests\Rsi\CommLink\CommLinkSearchRequest;
use App\Http\Requests\Rsi\CommLink\ReverseImageLinkSearchRequest;
use App\Http\Requests\Rsi\CommLink\ReverseImageSearchRequest;
use App\Models\Rsi\CommLink\CommLink;
use App\Models\Rsi\CommLink\Image\Image;
use App\Models\Rsi\CommLink\Image\ImageHash as ImageHashModel;
use App\Services\ImageHash\Implementations\PerceptualHash2;
use App\Services\Parser\CommLink\Image as ImageParser;
use App\Transformers\Api\V1\Rsi\CommLink\CommLinkTransformer;
use App\Transformers\Api\V1\Rsi\CommLink\Image\ImageHashTransformer;
use Dingo\Api\Http\Request;
use Dingo\Api\Http\Response;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Jenssegers\ImageHash\ImageHash;
use Jenssegers\ImageHash\Implementations\AverageHash;
use Jenssegers\ImageHash\Implementations\DifferenceHash;
use Symfony\Component\HttpKernel\Exception\HttpException;
use OpenApi\Attributes as OA;

class CommLinkSearchController extends ApiController
{
    /**
     * CommLinkController constructor.
     *
     * @param Request             $request
     * @param CommLinkTransformer $transformer
     */
    public function __construct(Request $request, CommLinkTransformer $transformer)
    {
        $this->transformer = $transformer;

        parent::__construct($request);
    }

    #[OA\Post(
        path: '/api/comm-links/search',
        requestBody: new OA\RequestBody(
            description: '(Partial) Comm-Link Title or ID',
            required: true,
            content: [
                new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        schema: 'query',
                        type: 'json',
                    ),
                    example: '{"query": "Banu Merchantman"}',
                )
            ]
        ),
        tags: ['Comm-Links', 'RSI-Website'],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/locale'),
        ],
        responses: [
            new OA\Response(
                ref: '#/components/schemas/comm_link',
                response: 200,
                description: 'A singular Comm-Link',
            ),
            new OA\Response(
                response: 404,
                description: 'No Comm-Link with found.',
            )
        ],
    )]
    public function searchByTitle(Request $request): Response
    {
        $request->validate((new CommLinkSearchRequest())->rules());

        $query = $request->get('keyword') ?? $request->get('query');

        return $this->disablePagination()
            ->getResponse(
                CommLink::query()
                    ->where('title', 'LIKE', sprintf('%%%s%%', $query))
                    ->orWhere('cig_id', 'LIKE', "%{$query}%")
                    ->limit(100)
            );
    }

    #[OA\Post(
        path: '/api/comm-links/reverse-image-link-search',
        requestBody: new OA\RequestBody(
            description: 'Url to an image hosted on (media.)robertsspaceindustries.com',
            required: true,
            content: [
                'url' => new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        schema: 'url',
                        type: 'json',
                    ),
                    example: '{"url": "https://robertsspaceindustries.com/i/cc75a45005a236c6e015dfc2782a2f55ed1e84a2/ADdPNihJzmPbNuTnFsH1DqUeqBRpXdSXVVtgJTyDDgscGKrzJuoFjResiiucPBBDeyrBscqRyZz4qxNsSbWvqUwdG/alien-week-2022-front.webp"}',
                )
            ]
        ),
        tags: ['Comm-Links', 'RSI-Website'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of Comm-Links that use that image',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/comm_link_link')
                )
            ),
            new OA\Response(
                response: 404,
                description: 'No Comm-Link found.',
            )
        ],
    )]
    public function reverseImageLinkSearch(Request $request)
    {
        $request->validate((new ReverseImageLinkSearchRequest())->rules());

        /** @var Image $image */
        $image = Image::query()
            ->where(
                'dir',
                $this->getDirHashFromImageUrl($request->get('url', ''))
            )
            ->firstOr(
                ['*'],
                function () {
                    return [];
                }
            );

        return $this->disablePagination()
            ->getResponse(optional($image)->commLinks);
    }

    #[OA\Post(
        path: '/api/comm-links/reverse-image-search',
        requestBody: new OA\RequestBody(
            required: true,
            content: [
                'image' => new OA\MediaType(
                    mediaType: 'application/octet-stream',
                    schema: new OA\Schema(
                        schema: 'image',
                        description: 'The image to reverse-search',
                        type: 'string',
                        format: 'binary',
                    ),
                ),
            ]
        ),
        tags: ['Comm-Links', 'RSI-Website'],
        parameters: [
            new OA\Parameter(
                name: 'similarity',
                in: 'query',
                required: true,
                schema: new OA\Schema(
                    schema: 'image_similarity',
                    type: 'integer',
                    maximum: 100,
                    minimum: 1,
                )
            ),
            new OA\Parameter(
                name: 'method',
                in: 'query',
                required: true,
                schema: new OA\Schema(
                    schema: 'image_search_method',
                    collectionFormat: 'csv',
                    default: 'perceptual',
                    enum: [
                        'perceptual',
                        'difference',
                        'average',
                    ]
                )
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of Comm-Links that use that image',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/comm_link_link')
                )
            ),
            new OA\Response(
                response: 404,
                description: 'No Comm-Link found.',
            )
        ],
    )]
    public function reverseImageSearch(Request $request): Response
    {
        $this->checkExtensionsLoaded();

        $request->validate((new ReverseImageSearchRequest())->rules());

        $this->transformer = new ImageHashTransformer();
        $this->transformer->includeAllAvailableIncludes();

        $hashConfig = $this->getHashConfigForMethod($request->get('method'));
        $hashConfig['similarity'] = (int)$request->get('similarity');
        $hashData = $this->hashImage($hashConfig['hasher'], $request->file('image'));

        return $this->disablePagination()
            ->getResponse(
                $this->getHashesFromDatabase($hashConfig, $hashData)
                    ->map(
                        function (object $data) {
                            $id = $data->comm_link_image_id;
                            $image = Image::query()->find($id);
                            $image->similarity = round((1 - $data->distance / 64) * 100);

                            return $image;
                        }
                    )
                    ->sortByDesc('similarity')
            );
    }

    /**
     * Returns the RSI directory hash of an image url
     *
     * @param string $url The RSI Media URl
     *
     * @return string The directory hash of the image
     */
    private function getDirHashFromImageUrl(string $url): string
    {
        return ImageParser::getDirHash(
            parse_url(
                ImageParser::cleanImgSource($url),
                PHP_URL_PATH
            )
        );
    }

    /**
     * Checks if either GD or Imagick is loaded
     *
     * @throws HttpException
     */
    private function checkExtensionsLoaded(): void
    {
        if (!extension_loaded('gd') && !extension_loaded('imagick')) {
            app('Log')::error('Required extension "GD" or "Imagick" not available.');

            $this->response->error('Required extension "GD" or "Imagick" not available.', 501);
        }
    }

    /**
     * Hash config based on hash method
     *
     * @param string $hashMethod
     *
     * @return array
     */
    private function getHashConfigForMethod(string $hashMethod): array
    {
        switch ($hashMethod) {
            case 'average':
                return [
                    'hasher' => new ImageHash(new AverageHash()),
                    'prefix' => 'a',
                    'table' => 'average_hash',
                ];

            case 'difference':
                return [
                    'hasher' => new ImageHash(new DifferenceHash()),
                    'prefix' => 'd',
                    'table' => 'difference_hash',
                ];

            case 'perceptual':
            default:
                return [
                    'hasher' => new ImageHash(new PerceptualHash2()),
                    'prefix' => 'p',
                    'table' => 'perceptual_hash',
                ];
        }
    }

    /**
     * Hashes an uploaded image
     *
     * @param ImageHash    $hasher The hasher with set hash method
     * @param UploadedFile $file   The uploaded file
     *
     * @return array
     */
    private function hashImage(ImageHash $hasher, $file): array
    {
        $hash = $hasher->hash($file)->toHex();

        return [
            'hash' => $hash,
            'decoded' => array_map('hexdec', (str_split($hash, strlen($hash) / 2))),
        ];
    }

    /**
     * Return hashes based on database connection type
     *
     * @param array $hashConfig
     * @param array $hashData
     *
     * @return Builder[]|Collection|\Illuminate\Support\Collection
     */
    private function getHashesFromDatabase(array $hashConfig, array $hashData)
    {
        // Since SQLITE does not support the BIT_COUNT operation we only search for exact hash matches
        if (config('database.default') === 'sqlite') {
            return $this->getHashesFromSQLiteStore($hashConfig['table'], $hashData['hash']);
        }

        return $this->getHashesFromSQLStore(
            $hashConfig['prefix'],
            $hashData['decoded'],
            $hashConfig['similarity']
        );
    }

    /**
     * Get the image hashes that equal the provided hash
     *
     * @param string $hashMethod Hash method average, distance, perceptual
     * @param string $hash       The image hash
     *
     * @return Builder[]|Collection
     */
    private function getHashesFromSQLiteStore(string $hashMethod, string $hash)
    {
        return ImageHashModel::query()
            ->where($hashMethod, $hash)
            ->get('comm_link_image_id');
    }

    /**
     * Get the image hashes matching the provided hash method and hamming distance
     *
     * @param string $prefix      Hash Attribute prefix
     * @param array  $decodedHash Image hash split in the middle and hex decoded
     * @param int    $distance    The maximum hamming distance
     *
     * @return \Illuminate\Support\Collection
     */
    private function getHashesFromSQLStore(
        string $prefix,
        array $decodedHash,
        int $distance
    ): \Illuminate\Support\Collection {
        return DB::table('comm_link_image_hashes')
            ->select('comm_link_image_id')
            ->selectRaw(
                'BIT_COUNT(' . $prefix . '_hash_1 ^ ?) + BIT_COUNT(' . $prefix . '_hash_2 ^ ?) AS distance',
                [
                    $decodedHash[0],
                    $decodedHash[1],
                ]
            )
            ->havingRaw('distance <= ?', [$distance])
            ->limit(50)
            ->get();
    }
}
