<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adverts', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->timestamp('online_at')->nullable()->default(null);
            $table->timestamp('ended_at')->nullable()->default(null);
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('category_id')->unsigned()->index();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->enum('type', ['bid', 'request']);
            $table->string('title',config('db_limits.adverts.maxTitle'));
            $table->string('slug');
            $table->text('description');
            $table->integer('price')->unsigned();
            $table->mediumInteger('price_coefficient')->unsigned()->default(0);
            $table->integer('price_margin')->unsigned();
            $table->decimal('price_margin_decimal',12,5)->unsigned();
            $table->string('currency', 3);
            $table->decimal('latitude',6,4);
            $table->decimal('longitude',19,16);
            foreach (\App\Common\GeoManager::$accurate as $key){
                $table->string($key)->nullable()->default(null);
            }
            $table->string('geoloc')->nullable()->default(null);
            $table->string('mainPicture',32);
            $table->boolean('isPublish')->default(false);
            $table->boolean('isValid')->nullable()->default(null);
            $table->mediumInteger('totalQuantity')->unsigned();
            $table->mediumInteger('lotMiniQuantity')->unsigned()->nullable()->default(null);
            $table->boolean('isUrgent')->default(false);
            $table->mediumInteger('views')->unsigned()->default(0);
            $table->tinyInteger('lastObsoleteMail')->unsigned()->nullable()->default(null);
            $table->boolean('is_delegation')->default(false);
            $table->bigInteger('video_id')->unsigned()->nullable()->default(null);
            $table->string('nextUrl')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('adverts');
    }
}
