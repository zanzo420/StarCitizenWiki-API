<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            /** DROP Foreign Key Constraints */
            Schema::table('comm_link_translations', static fn (Blueprint $table) => $table->dropForeign(['locale_code']));
            Schema::table('sc_item_translations', static fn (Blueprint $table) => $table->dropForeign('fk_sc_i_tra_locale'));
            Schema::table('star_citizen_unpacked_item_translations', static fn (Blueprint $table) => $table->dropForeign('sc_unpacked_item_translations_locale'));
            Schema::table('galactapedia_article_translations', static fn (Blueprint $table) => $table->dropForeign(['locale_code']));
            Schema::table('manufacturer_translations', static fn (Blueprint $table) => $table->dropForeign(['locale_code']));
            Schema::table('production_note_translations', static fn (Blueprint $table) => $table->dropForeign(['locale_code']));
            Schema::table('production_status_translations', static fn (Blueprint $table) => $table->dropForeign(['locale_code']));
            Schema::table('celestial_object_translations', static fn (Blueprint $table) => $table->dropForeign(['locale_code']));
            Schema::table('starsystem_translations', static fn (Blueprint $table) => $table->dropForeign(['locale_code']));
            Schema::table('vehicle_focus_translations', static fn (Blueprint $table) => $table->dropForeign(['locale_code']));
            Schema::table('vehicle_size_translations', static fn (Blueprint $table) => $table->dropForeign(['locale_code']));
            Schema::table('vehicle_type_translations', static fn (Blueprint $table) => $table->dropForeign(['locale_code']));
            Schema::table('vehicle_translations', static fn (Blueprint $table) => $table->dropForeign(['locale_code']));
            Schema::table('transcript_translations', static fn (Blueprint $table) => $table->dropForeign('new_transcript_locale_code'));
            Schema::table('relay_transcript_translations', static fn (Blueprint $table) => $table->dropForeign('transcript_translations_locale_code_foreign'));
        }

        Schema::table('languages', static fn (Blueprint $table) => $table->char('locale_code', 25)->change());
        Schema::table('comm_link_translations', static fn (Blueprint $table) => $table->char('locale_code', 25)->change());
        Schema::table('sc_item_translations', static fn (Blueprint $table) => $table->char('locale_code', 25)->change());
        Schema::table('star_citizen_unpacked_item_translations', static fn (Blueprint $table) => $table->char('locale_code', 25)->change());
        Schema::table('galactapedia_article_translations', static fn (Blueprint $table) => $table->char('locale_code', 25)->change());
        Schema::table('manufacturer_translations', static fn (Blueprint $table) => $table->char('locale_code', 25)->change());
        Schema::table('production_note_translations', static fn (Blueprint $table) => $table->char('locale_code', 25)->change());
        Schema::table('production_status_translations', static fn (Blueprint $table) => $table->char('locale_code', 25)->change());
        Schema::table('celestial_object_translations', static fn (Blueprint $table) => $table->char('locale_code', 25)->change());
        Schema::table('starsystem_translations', static fn (Blueprint $table) => $table->char('locale_code', 25)->change());
        Schema::table('vehicle_focus_translations', static fn (Blueprint $table) => $table->char('locale_code', 25)->change());
        Schema::table('vehicle_size_translations', static fn (Blueprint $table) => $table->char('locale_code', 25)->change());
        Schema::table('vehicle_type_translations', static fn (Blueprint $table) => $table->char('locale_code', 25)->change());
        Schema::table('vehicle_translations', static fn (Blueprint $table) => $table->char('locale_code', 25)->change());
        Schema::table('transcript_translations', static fn (Blueprint $table) => $table->char('locale_code', 25)->change());
        Schema::table('relay_transcript_translations', static fn (Blueprint $table) => $table->char('locale_code', 25)->change());

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('languages', static fn (Blueprint $table) => $table->char('locale_code', 5)->change());
        Schema::table('comm_link_translations', static fn (Blueprint $table) => $table->char('locale_code', 5)->change());
        Schema::table('sc_item_translations', static fn (Blueprint $table) => $table->char('locale_code', 5)->change());
        Schema::table('star_citizen_unpacked_item_translations', static fn (Blueprint $table) => $table->char('locale_code', 5)->change());
        Schema::table('galactapedia_article_translations', static fn (Blueprint $table) => $table->char('locale_code', 5)->change());
        Schema::table('manufacturer_translations', static fn (Blueprint $table) => $table->char('locale_code', 5)->change());
        Schema::table('production_note_translations', static fn (Blueprint $table) => $table->char('locale_code', 5)->change());
        Schema::table('production_status_translations', static fn (Blueprint $table) => $table->char('locale_code', 5)->change());
        Schema::table('celestial_object_translations', static fn (Blueprint $table) => $table->char('locale_code', 5)->change());
        Schema::table('starsystem_translations', static fn (Blueprint $table) => $table->char('locale_code', 5)->change());
        Schema::table('vehicle_focus_translations', static fn (Blueprint $table) => $table->char('locale_code', 5)->change());
        Schema::table('vehicle_size_translations', static fn (Blueprint $table) => $table->char('locale_code', 5)->change());
        Schema::table('vehicle_type_translations', static fn (Blueprint $table) => $table->char('locale_code', 5)->change());
        Schema::table('vehicle_translations', static fn (Blueprint $table) => $table->char('locale_code', 5)->change());
        Schema::table('transcript_translations', static fn (Blueprint $table) => $table->char('locale_code', 5)->change());
        Schema::table('relay_transcript_translations', static fn (Blueprint $table) => $table->char('locale_code', 5)->change());

        /** Re-Add Keys */
        Schema::table('comm_link_translations', static fn (Blueprint $table) => $table->foreign('locale_code')->references('locale_code')->on('languages'));
        Schema::table('sc_item_translations', static fn (Blueprint $table) => $table->foreign('locale_code')->references('locale_code')->on('languages'));
        Schema::table('star_citizen_unpacked_item_translations', static fn (Blueprint $table) => $table->foreign('locale_code')->references('locale_code')->on('languages'));
        Schema::table('galactapedia_article_translations', static fn (Blueprint $table) => $table->foreign('locale_code')->references('locale_code')->on('languages'));
        Schema::table('manufacturer_translations', static fn (Blueprint $table) => $table->foreign('locale_code')->references('locale_code')->on('languages'));
        Schema::table('production_note_translations', static fn (Blueprint $table) => $table->foreign('locale_code')->references('locale_code')->on('languages'));
        Schema::table('production_status_translations', static fn (Blueprint $table) => $table->foreign('locale_code')->references('locale_code')->on('languages'));
        Schema::table('celestial_object_translations', static fn (Blueprint $table) => $table->foreign('locale_code')->references('locale_code')->on('languages'));
        Schema::table('starsystem_translations', static fn (Blueprint $table) => $table->foreign('locale_code')->references('locale_code')->on('languages'));
        Schema::table('vehicle_focus_translations', static fn (Blueprint $table) => $table->foreign('locale_code')->references('locale_code')->on('languages'));
        Schema::table('vehicle_size_translations', static fn (Blueprint $table) => $table->foreign('locale_code')->references('locale_code')->on('languages'));
        Schema::table('vehicle_type_translations', static fn (Blueprint $table) => $table->foreign('locale_code')->references('locale_code')->on('languages'));
        Schema::table('vehicle_translations', static fn (Blueprint $table) => $table->foreign('locale_code')->references('locale_code')->on('languages'));
        Schema::table('vehicle_translations', static fn (Blueprint $table) => $table->foreign('locale_code')->references('locale_code')->on('languages'));
        Schema::table('transcript_translations', static fn (Blueprint $table) => $table->foreign('locale_code')->references('locale_code')->on('languages'));
        Schema::table('relay_transcript_translations', static fn (Blueprint $table) => $table->foreign('locale_code')->references('locale_code')->on('languages'));
    }
};
