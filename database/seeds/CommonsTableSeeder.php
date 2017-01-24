<?php

use App\Parameters;
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
        $parameters = new Parameters();
        $parameters->save();
    }
}
