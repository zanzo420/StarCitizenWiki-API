<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\SC\Item\ItemTranslation;
use App\Models\SC\Mission\GiverTranslation;
use App\Models\SC\Mission\Translation;
use App\Models\StarCitizen\Starmap\CelestialObject\CelestialObjectTranslation;
use App\Models\StarCitizen\Starmap\Starsystem\StarsystemTranslation;
use App\Models\System\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'translation_v2',
    title: 'Grouped Translations',
    description: 'Translations of an entity',
    properties: [
        new OA\Property(property: Language::ENGLISH, type: 'string'),
        new OA\Property(property: Language::GERMAN, type: 'string'),
        new OA\Property(property: Language::CHINESE, type: 'string'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'translation_single_v2',
    title: 'Single Translation',
    description: 'Translation of an entity',
    type: 'string'
)]
abstract class AbstractTranslationResource extends AbstractBaseResource
{
    protected function getTranslation($model, Request $request, $translationKey = 'translation')
    {
        /** @var Collection $translations */
        $translations = $model->translationsCollection();

        $locale = $request->get('locale');

        if (! empty($locale)) {
            return $this->getSingleTranslation($translations, $request->get('locale'), $translationKey);
        }

        $translations = $translations->map(
            function ($translation) use ($translationKey, $translations) {
                if ($translation instanceof Language) {
                    return $this->getSingleTranslation($translations, Language::ENGLISH, $translationKey);
                }

                return $this->getSingleTranslation($translation, $translationKey);
            }
        )->filter(
            function ($translations) {
                return ! empty($translations);
            }
        );

        return $translations->isEmpty() ? null : $translations;
    }

    private function getSingleTranslation($translations, string $locale, $translationKey = 'translation'): ?string
    {
        $translation = null;

        if (
            $translations instanceof ItemTranslation ||
            $translations instanceof Translation ||
            $translations instanceof GiverTranslation ||
            $translations instanceof CelestialObjectTranslation ||
            $translations instanceof StarsystemTranslation
        ) {
            return $translations[$translationKey];
        }

        if ($translations->has($locale) && ! $translations->get($locale) instanceof Language) {
            $translation = $translations->get($locale)[$translationKey];
        } elseif ($translations->has(Language::ENGLISH)) {
            $translation = $translations->get(Language::ENGLISH)[$translationKey];
        }

        return $translation;
    }
}
