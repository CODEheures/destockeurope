<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveFilesInfosOnStats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stats', function (Blueprint $table) {
            $table->dropColumn(['countLocalFiles', 'sizeLocalFiles', 'countDistantFiles', 'sizeDistantFiles']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stats', function (Blueprint $table) {
            $table->mediumInteger('countLocalFiles')->unsigned()->default(0);
            $table->integer('sizeLocalFiles')->unsigned()->default(0);
            $table->mediumInteger('countDistantFiles')->unsigned()->default(0);
            $table->integer('sizeDistantFiles')->unsigned()->default(0);
        });
    }
}
