<?php

declare(strict_types=1);

namespace App\Console\Commands\SC\Wiki;

use App\Console\Commands\AbstractQueueCommand;
use App\Traits\GetWikiCsrfTokenTrait;
use App\Traits\Jobs\CreateEnglishSubpageTrait;
use ErrorException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use StarCitizenWiki\MediaWikiApi\Facades\MediaWikiApi;

abstract class AbstractCreateWikiPage extends AbstractQueueCommand
{
    use CreateEnglishSubpageTrait;
    use GetWikiCsrfTokenTrait;

    protected string $itemTemplate = <<<'ITEM'
{{Item
|uuid = <UUID>
|image = {{Find image}}
|name = <ITEM NAME>
|manufacturer = <MANUFACTURER CODE>
}}
ITEM;

    protected string $template;

    final public function uploadWiki($model, string $summary): void
    {
        try {
            $token = $this->getCsrfToken('services.wiki_translations');
            $response = MediaWikiApi::edit($this->getPageName($model))
                ->withAuthentication()
                ->text($this->getPageText($model))
                ->csrfToken($token)
                ->summary($summary)
                ->request([
                    'timeout' => 600,
                ]);
        } catch (ErrorException|GuzzleException $e) {
            $this->error($e->getMessage());

            return;
        }

//        $this->createEnglishSubpage($this->getPageName($model), $token);

        if ($response->hasErrors() && $response->getErrors()['code'] !== 'articleexists') {
            $this->error(implode(', ', $response->getErrors()));
        }
    }

    abstract protected function prepareTemplate($model): string;

    abstract protected function getPageName($model): string;

    abstract protected function getManufacturerCode($model): string;

    protected function getUUID($model): string
    {
        return $model->uuid;
    }

    protected function getPageText($model): string
    {
        $name = $this->getPageName($model);

        $itemTemplate = $this->itemTemplate;
        $itemTemplate = str_replace('<UUID>', $this->getUUID($model), $itemTemplate);
        $itemTemplate = str_replace('<ITEM NAME>', $name, $itemTemplate);
        $itemTemplate = str_replace('<MANUFACTURER CODE>', $this->getManufacturerCode($model), $itemTemplate);

        $originalTemplate = $this->template;

        $this->template = str_replace(
            '<CURDATE>',
            '2024-04-01',
            //Carbon::now()->format('Y-m-d'),
            $this->template
        );
        $this->template = str_replace(
            '<MANUFACTURER CODE>',
            $this->getManufacturerCode($model),
            $this->template
        );
        $this->template = str_replace(
            '<ITEM NAME>',
            $name,
            $this->template
        );

        $this->template = sprintf("%s\n%s", $itemTemplate, $this->template);

        $text = $this->prepareTemplate($model);
        $this->reset();
        $this->template = $originalTemplate;

        return $text;
    }

    protected function fixText(string $type, string &$text, array $additions = []): string
    {
        $needles = [
            'a',
            'e',
            'i',
            'o',
            'u',
            'panzerung',
            ...$additions,
        ];

        if (Str::endsWith($type, $needles)) {
            $text = str_replace('ist ein ', 'ist eine ', $text);
            $text = str_replace('(r)', '', $text);
        } else {
            $text = str_replace('(r)', 'r', $text);
        }

        return $text;
    }

    private function reset(): void
    {
        $this->template = <<<'ITEM'
{{Item
|uuid = <UUID>
|image =
|name = <ITEM NAME>
|manufacturer = <MANUFACTURER CODE>
}}
ITEM;
    }
}
