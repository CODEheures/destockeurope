<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stats', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->mediumInteger('totalAdverts')->unsigned()->default(0);
            $table->smallInteger('totalInvalidAdverts')->unsigned()->default(0);
            $table->smallInteger('totalWaitingAdverts')->unsigned()->default(0);
            $table->mediumInteger('totalNewViews')->unsigned()->default(0);
            $table->mediumInteger('totalNewFreeAdverts')->unsigned()->default(0);
            $table->mediumInteger('totalNewCostAdverts')->unsigned()->default(0);
            $table->integer('totalCosts')->unsigned()->default(0);
            $table->mediumInteger('countLocalFiles')->unsigned()->default(0);
            $table->integer('sizeLocalFiles')->unsigned()->default(0);
            $table->mediumInteger('countDistantFiles')->unsigned()->default(0);
            $table->integer('sizeDistantFiles')->unsigned()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stats');
    }
}
