<?php declare(strict_types = 1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAffiliationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'affiliations',
            function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('cig_id');
                $table->string('name');
                $table->string('code');
                $table->string('color');
                $table->unsignedInteger('membership_id')->nullable();

                $table->unique('name');
                $table->unique('code');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('affiliations');
    }
}
