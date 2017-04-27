<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnonymousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anonymouses', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('name')->nullable()->default(null);
            $table->string('email')->unique();
            $table->string('compagnyName',config('db_limits.users.maxCompagnyName'))->nullable()->default(null);
            $table->string('phone',config('db_limits.users.maxPhone'))->nullable()->default(null);
            $table->boolean('isNewsLetterSubscriber')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('anonymouses');
    }
}
