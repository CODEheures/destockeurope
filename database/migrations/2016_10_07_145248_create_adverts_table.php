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
            $table->integer('user_id')->unsigned()->index();
            $table->integer('category_id')->unsigned()->index();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->integer('payment_id')->unsigned()->index()->nullable()->default(null);
            $table->enum('type', ['bid', 'request']);
            $table->string('title',config('db_limits.adverts.maxTitle'));
            $table->text('description');
            $table->integer('price');
            $table->string('currency', 3);
            $table->decimal('latitude',6,4);
            $table->decimal('longitude',19,16);
            $table->string('geoloc')->nullable()->default(null);
            $table->string('mainPicture',32);
            $table->boolean('isPublish')->default(false);
            $table->boolean('isValid')->nullable()->default(null);
            $table->decimal('cost',8,2)->default(0);
            $table->integer('totalQuantity');
            $table->integer('lotMiniQuantity')->nullable()->default(null);
            $table->boolean('urgent')->default(false);
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
