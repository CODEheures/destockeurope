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
            $table->timestamps();
            $table->softDeletes();
            $table->rememberToken();
            $table->string('facebook_id')->unique()->nullable()->default(null);
            $table->string('google_id')->unique()->nullable()->default(null);
            $table->string('twitter_id')->unique()->nullable()->default(null);
            $table->string('github_id')->unique()->nullable()->default(null);
            $table->string('name');
            $table->string('email')->unique()->nullable()->default(null);
            $table->string('password', 60)->nullable()->default(null);
            $table->boolean('confirmed')->default(false);
            $table->string('confirmationToken',60)->nullable()->default(null);
            $table->string('role')->default(\App\User::ROLES[\App\User::ROLE_USER]); //user, admin
            $table->string('avatar')->nullable()->default(null);
            $table->string('currency', 3)->default(env('DEFAULT_CURRENCY'));
            $table->string('locale',50)->default(env('DEFAULT_LOCALE'));
            $table->string('compagnyName',config('db_limits.users.maxCompagnyName'))->nullable()->default(null);
            $table->string('registrationNumber', config('db_limits.users.maxRegistrationNumber'))->nullable()->default(null);
            $table->string('requesterNumber', config('db_limits.users.maxRegistrationNumber'))->nullable()->default(null);
            $table->string('vatIdentifier')->nullable()->default(null);
            $table->string('phone',config('db_limits.users.maxPhone'))->nullable()->default(null);
            $table->decimal('latitude',6,4)->nullable()->default(null);
            $table->decimal('longitude',19,16)->nullable()->default(null);
            $table->text('geoloc')->nullable()->default(null);
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
