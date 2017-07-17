<?php

use App\Advert;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDiscountOnTotalToAdverts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('adverts', function (Blueprint $table) {
            $table->mediumInteger('discount_on_total')->unsigned()->default(0)->after('price_margin_decimal');
            $table->mediumInteger('price_coefficient_total')->unsigned()->default(0)->after('discount_on_total');
        });

        Advert::withTrashed()->update(["price_coefficient_total" => \Illuminate\Support\Facades\DB::raw("`price_coefficient`")]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('adverts', function (Blueprint $table) {
            $table->dropColumn(['discount_on_total', 'price_coefficient_total']);
        });
    }
}
