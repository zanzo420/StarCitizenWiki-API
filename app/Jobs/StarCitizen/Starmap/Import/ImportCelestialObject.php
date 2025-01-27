<?php

declare(strict_types=1);

namespace App\Jobs\StarCitizen\Starmap\Import;

use App\Models\StarCitizen\Starmap\CelestialObject\CelestialObject as CelestialObjectModel;
use App\Models\System\Language;
use App\Services\Parser\Starmap\Affiliation;
use App\Services\Parser\Starmap\CelestialSubtype;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

/**
 * Class ParseCelestialObject
 */
class ImportCelestialObject implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected Collection $rawData;

    /**
     * @var int Starsystem Id
     */
    private int $starsystemId;

    /**
     * Create a new job instance.
     *
     * @param  array|Collection  $rawData
     */
    public function __construct($rawData, int $starsystemId)
    {
        $this->rawData = new Collection($rawData);
        $this->starsystemId = $starsystemId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (empty($this->rawData['subtype'])) {
            app('Log')::debug('Parse Celestial Object: empty=true');
        }

        $data = $this->getData();
        $description = $data->pull('description');

        /** @var CelestialObjectModel $celestialObject */
        $celestialObject = CelestialObjectModel::updateOrCreate(
            [
                'code' => $data->pull('code'),
                'starsystem_id' => $data->pull('starsystem_id'),
            ],
            $data->toArray()
        );

        if ($description !== null) {
            $celestialObject->translations()->updateOrCreate(
                [
                    'celestial_object_id' => $celestialObject->id,
                    'locale_code' => Language::ENGLISH,
                ],
                [
                    'translation' => $description,
                ]
            );
        }

        $celestialObject->affiliation()->sync($this->getAffiliationIds($this->rawData->pull('affiliation')));
    }

    public function getData(): Collection
    {
        return new Collection(
            [
                'cig_id' => $this->rawData->get('id'),
                'starsystem_id' => $this->starsystemId,

                'age' => $this->rawData->get('age'),
                'appearance' => $this->rawData->get('appearance'),
                'axial_tilt' => $this->rawData->get('axial_tilt'),
                'code' => $this->rawData->get('code'),
                'designation' => $this->rawData->get('designation'),
                'distance' => $this->rawData->get('distance'),
                'fairchanceact' => $this->rawData->get('fairchanceact'),
                'habitable' => $this->rawData->get('habitable'),
                'info_url' => $this->rawData->get('info_url'),
                'latitude' => $this->rawData->get('latitude'),
                'longitude' => $this->rawData->get('longitude'),
                'name' => $this->rawData->get('name'),
                'orbit_period' => $this->rawData->get('orbit_period'),
                'parent_id' => $this->rawData->get('parent_id'),
                'sensor_danger' => $this->rawData->get('sensor_danger'),
                'sensor_economy' => $this->rawData->get('sensor_economy'),
                'sensor_population' => $this->rawData->get('sensor_population'),

                'size' => $this->rawData->get('size'),
                'type' => $this->rawData->get('type'),

                'subtype_id' => $this->getCelestialSubtypeId(),
                'time_modified' => $this->rawData->get('time_modified'),

                'description' => $this->rawData->get('description'),
            ]
        );
    }

    /**
     * @return mixed
     */
    private function getCelestialSubtypeId()
    {
        $parser = new CelestialSubtype($this->rawData['subtype']);

        return optional($parser->getCelestialSubtype())->id;
    }

    private function getAffiliationIds(array $affiliations): array
    {
        return collect($affiliations)
            ->filter(
                function ($affiliation) {
                    return isset($affiliation['id']);
                }
            )
            ->map(
                function ($affiliationData) {
                    return (new Affiliation($affiliationData))->getAffiliation();
                }
            )
            ->map(
                function (\App\Models\StarCitizen\Starmap\Affiliation $affiliation) {
                    return $affiliation->id;
                }
            )
            ->toArray();
    }
}
