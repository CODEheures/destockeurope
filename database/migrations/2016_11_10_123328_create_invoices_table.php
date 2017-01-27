<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('user_id')->unsigned()->index();
            $table->integer('invoice_number')->unsigned()->nullable()->default(null);
            $table->tinyInteger('method')->unsigned()->nullable()->default(null);
            $table->string('authorization')->nullable()->default(null);
            $table->string('captureId')->nullable()->default(null);
            $table->string('voidId')->nullable()->default(null);
            $table->text('options')->nullable()->default(null);
            $table->mediumInteger('cost')->unsigned()->default(0);
            $table->string('tva_customer')->nullable()->default(null);
            $table->string('tva_requester')->nullable()->default(null);
            $table->string('vatIdentifier')->nullable()->default(null);
            $table->boolean('tvaSubject')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
