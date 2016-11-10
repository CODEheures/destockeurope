<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique()->nullable()->default(null);
            $table->string('password', 60)->nullable()->default(null);
            $table->boolean('confirmed')->default(false);
            $table->string('confirmation_token',60)->nullable()->default(null);
            $table->string('role')->default('user'); //user, admin
            $table->string('facebook_id')->unique()->nullable()->default(null);
            $table->string('google_id')->unique()->nullable()->default(null);
            $table->string('twitter_id')->unique()->nullable()->default(null);
            $table->string('github_id')->unique()->nullable()->default(null);
            $table->string('avatar')->nullable()->default(null);
            $table->string('currency', 3)->default("EUR");
            $table->string('locale',50)->default("fr_FR");
            $table->string('compagny_name',config('db_limits.users.maxCompagnyName'))->nullable()->default(null);
            $table->string('registration_number', config('db_limits.users.maxRegistrationNumber'))->nullable()->default(null);
            $table->decimal('latitude',6,4)->nullable()->default(null);
            $table->decimal('longitude',19,16)->nullable()->default(null);
            $table->text('geoloc')->nullable()->default(null);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
