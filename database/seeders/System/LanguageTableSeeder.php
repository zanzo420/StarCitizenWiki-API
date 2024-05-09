<?php

declare(strict_types=1);

namespace Database\Seeders\System;

use App\Models\System\Language;
use Illuminate\Database\Seeder;

class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Language::query()->firstOrCreate(['locale_code' => Language::ENGLISH]);
        Language::query()->firstOrCreate(['locale_code' => Language::GERMAN]);
        Language::query()->firstOrCreate(['locale_code' => Language::CHINESE]);
    }
}
