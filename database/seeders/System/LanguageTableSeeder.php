<?php

declare(strict_types=1);

namespace Database\Seeders\System;

use App\Models\System\Language;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        if (DB::table('languages')->where('locale_code', Language::ENGLISH)->exists()) {
            return;
        }

        $now = Carbon::now();

        DB::table('languages')->insert(
            [
                'locale_code' => Language::ENGLISH,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );
        DB::table('languages')->insert(
            [
                'locale_code' => Language::GERMAN,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );
        DB::table('languages')->insert(
            [
                'locale_code' => Language::CHINESE,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );
    }
}
