<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveEmailKeyFromUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (config('database.connection') === 'mysql') {
            Schema::table('users', function (Blueprint $table) {
                $table->dropUnique('email');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (config('database.connection') === 'mysql') {
            Schema::table('users', function (Blueprint $table) {
                $table->unique('email');
            });
        }
    }
}
