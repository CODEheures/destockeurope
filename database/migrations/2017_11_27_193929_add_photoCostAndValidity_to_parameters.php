<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPhotoCostAndValidityToParameters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('parameters', function (Blueprint $table) {
            $table->smallInteger('photoCost')->default(6)->after('advertsPerPage');
            $table->date('validityCosts')->nullable()->default(null)->after('videoCost');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('parameters', function (Blueprint $table) {
            $table->dropColumn(['photoCost','validityCosts' ]);
        });
    }
}
