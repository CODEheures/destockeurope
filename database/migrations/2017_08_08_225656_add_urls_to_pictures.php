<?php

use App\Picture;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUrlsToPictures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pictures', function (Blueprint $table) {
            $table->string('thumbUrl');
            $table->string('normalUrl');
        });

        Picture::withTrashed()->where('isThumb', true)->forceDelete();
        $pictures = Picture::withTrashed()->get();
        foreach ($pictures as $picture) {
            $picture->update([
                $picture->thumbUrl = "http://statics.destockeurope.progress/thumb/1/" . $picture->hashName . "/jpg",
                $picture->normalUrl = "http://statics.destockeurope.progress/normal/1/" . $picture->hashName . "/jpg"
            ]);
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pictures', function (Blueprint $table) {
            $table->dropColumn(['thumbUrl', 'normalUrl']);
        });
    }
}
