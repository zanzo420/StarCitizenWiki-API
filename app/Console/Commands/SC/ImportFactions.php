<?php

namespace App\Console\Commands\SC;

use App\Models\SC\Faction\Faction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ImportFactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sc:import-factions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->withProgressBar(File::allFiles(scdata('factions')), function (string $file) {
            $data = File::json($file);

            if (str_starts_with($data['description'], '@')) {
                $data['description'] = null;
            }

            /** @var Faction $model */
            $model = Faction::query()->updateOrCreate([
                'uuid' => $data['__ref'],
            ], [
                'name' => $data['displayName'],
                'class_name' => $data['ClassName'],
                'description' => $data['description'],
                'game_token' => $data['gameToken'],
                'default_reaction' => $data['defaultReaction'],
            ]);

            collect($data['factionRelationships'])->each(function ($factionRelationship) use ($model) {
                $model->relations()->updateOrCreate([
                    'other_faction_uuid' => $factionRelationship['faction'],
                    'relation' => $factionRelationship['reactionType'],
                ]);
            });
        });
    }
}
