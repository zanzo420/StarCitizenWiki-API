<?php declare(strict_types = 1);

use Illuminate\Database\Seeder;

/**
 * Class ShortUrlWhitelistsTableSeeder
 */
class ShortUrlWhitelistsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('short_url_whitelists')->insert(
            [
                'url'        => 'robertsspaceindustries.com',
                'created_at' => '2017-01-01 00:00:00',
            ]
        );
        DB::table('short_url_whitelists')->insert(
            [
                'url'        => 'forums.robertsspaceindustries.com',
                'created_at' => '2017-01-01 00:00:00',
            ]
        );
        DB::table('short_url_whitelists')->insert(
            [
                'url'        => 'stargov.de',
                'created_at' => '2017-01-01 00:00:00',
                'internal'   => true,
            ]
        );
        DB::table('short_url_whitelists')->insert(
            [
                'url'        => 'star-citizen.wiki',
                'created_at' => '2017-01-01 00:00:00',
            ]
        );
        DB::table('short_url_whitelists')->insert(
            [
                'url'        => 'facebook.com',
                'created_at' => '2017-01-01 00:00:00',
            ]
        );
        DB::table('short_url_whitelists')->insert(
            [
                'url'        => 'forum.crashcorps.de',
                'created_at' => '2017-01-01 00:00:00',
            ]
        );
        DB::table('short_url_whitelists')->insert(
            [
                'url'        => 'starcitizenbase.de',
                'created_at' => '2017-01-01 00:00:00',
            ]
        );
        DB::table('short_url_whitelists')->insert(
            [
                'url'        => 'youtube.com',
                'created_at' => '2017-01-01 00:00:00',
            ]
        );
        DB::table('short_url_whitelists')->insert(
            [
                'url'        => 'youtu.be',
                'created_at' => '2017-01-01 00:00:00',
                'internal'   => true,
            ]
        );
    }
}
