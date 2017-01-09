<?php

use App\Common;
use Illuminate\Database\Seeder;

class CommonsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $parameters = new Common();
        $parameters->save();
    }
}
