<?php

declare(strict_types=1);

return [
    'english' => \App\Models\System\Language::ENGLISH,
    'german' => \App\Models\System\Language::GERMAN,
    'chinese' => \App\Models\System\Language::CHINESE,

    // @Todo Codes need to be updated if more are added
    'codes' => [
        'de_DE',
        'en_EN',
        'zh_CN',
    ],

    'enable_galactapedia_language_links' => env('GALACTAPEDIA_LANGUAGE_LINKS', false),
    'translate_wrap_galactapedia' => env('GALACTAPEDIA_TRANSLATE_WRAP', false),

    'translate_wrap_commlinks' => env('COMMLINKS_TRANSLATE_WRAP', false),
];
