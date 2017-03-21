<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parameters', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->boolean('masterAds')->default(false);
            $table->string('urlMasterAds')->nullable()->default(null);
            $table->string('urlLinkMasterAds')->nullable()->default(null);
            $table->smallInteger('offsetYMasterAds')->default(0);
            $table->tinyInteger('adsFrequency')->default(0);
            $table->tinyInteger('advertsPerPage')->default(6);
            $table->smallInteger('urgentCost')->default(49);
            $table->smallInteger('renewCost')->default(9);
            $table->smallInteger('videoCost')->default(9);
            $table->tinyInteger('nbFreePictures')->default(3);
            $table->tinyInteger('nbMaxPictures')->default(6);
            $table->enum('welcomeType', ['1', '2'])->default(2);
            $table->smallInteger('advertResumeLenght')->default(120);
            $table->tinyInteger('maxNumberOfSearchResults')->default(3);
            $table->tinyInteger('minLengthSearch')->default(3);
            $table->boolean('isOnTransfert')->default(false);
            $table->integer('transfertPartial')->unsigned()->default(0);
            $table->integer('transfertTotal')->unsigned()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parameters');
    }
}
