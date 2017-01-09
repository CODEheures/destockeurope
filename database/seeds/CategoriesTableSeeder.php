<?php

use Illuminate\Database\Seeder;
use App\Category;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rootCategory1 =new Category();
        $rootCategory1->description = ['fr' => 'Matériels', 'en'=>'Hardware'];
        $rootCategory1->saveAsRoot();

        $rootCategory2 = new Category();
        $rootCategory2->description = ['fr' => 'Textiles', 'en'=>'Textils'];
        $rootCategory2->saveAsRoot();

        $subCategory11 = new Category();
        $subCategory11->description = ['fr' => 'crayons', 'en'=>'pens'];

        $subCategory12 = new Category();
        $subCategory12->description = ['fr' => 'electroniques', 'en'=>'electronics'];


        $parent1 = Category::find($rootCategory1->id);
        $parent1->appendNode($subCategory11);
        $parent1->appendNode($subCategory12);


        $subCategory21 = new Category();
        $subCategory21->description = ['fr' => 'Habillement', 'en'=>'Wearing'];

        $subCategory22 = new Category();
        $subCategory22->description = ['fr' => 'Sacs', 'en'=>'Bags'];

        $parent2 = Category::find($rootCategory2->id);
        $parent2->appendNode($subCategory21);
        $parent2->appendNode($subCategory22);


        $subCategory211 = new Category();
        $subCategory211->description = ['fr' => 'femme', 'en'=>'women'];

        $subCategory212 = new Category();
        $subCategory212->description = ['fr' => 'homme', 'en'=>'men'];

        $subCategory213 = new Category();
        $subCategory213->description = ['fr' => 'bi', 'en'=>'bi'];

        $parent21 = Category::find($subCategory21->id);
        $parent21->appendNode($subCategory211);
        $parent21->appendNode($subCategory212);
        $parent21->appendNode($subCategory213);
    }
}
