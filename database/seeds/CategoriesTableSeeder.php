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

        /**
         *  ROOT 1
         */
        $rootCategory1 =new Category();
        $rootCategory1->description = ['fr' => 'Matériel Pro', 'en'=>'Pro Hardware'];
        $rootCategory1->saveAsRoot();

            $subCategory11 = new Category();
            $subCategory11->description = ['fr' => 'Outillages & équipements', 'en'=>'Tools & Equipment'];

                $subCategory111 = new Category();
                $subCategory111->description = ['fr' => 'Engins de chantier', 'en'=>'Construction machinery'];

                $subCategory112 = new Category();
                $subCategory112->description = ['fr' => html_entity_decode('&Eacute;').'quipement de construction', 'en'=>'Construction equipment'];

                $subCategory113 = new Category();
                $subCategory113->description = ['fr' => html_entity_decode('&Eacute;').'quipement & mobilier industriel', 'en'=>'Industrial Equipment & Furniture'];

                $subCategory114 = new Category();
                $subCategory114->description = ['fr' => 'Conditionnement & stockage', 'en'=>'Packaging & storage'];

                $subCategory115 = new Category();
                $subCategory115->description = ['fr' => html_entity_decode('&Eacute;').'quipement de collectivités', 'en'=>'Community equipment'];

                $subCategory116 = new Category();
                $subCategory116->description = ['fr' => 'Restauration - Hôtellerie', 'en'=>'Catering - Hotels'];

                $subCategory117 = new Category();
                $subCategory117->description = ['fr' => 'Outillage', 'en'=>'Tools'];

                $subCategory118 = new Category();
                $subCategory118->description = ['fr' => 'Outillage second œuvre', 'en'=>'Second Work Tools'];

                $subCategory119 = new Category();
                $subCategory119->description = ['fr' => 'Commerces & Marchés', 'en'=>'Shopping & Markets'];

            $subCategory12 = new Category();
            $subCategory12->description = ['fr' => html_entity_decode('&Eacute;').'nergie & Matériaux', 'en'=>'Energy & Materials'];

                $subCategory121 = new Category();
                $subCategory121->description = ['fr' => 'Matières premières', 'en'=>'Raw materials'];

                $subCategory122 = new Category();
                $subCategory122->description = ['fr' => html_entity_decode('&Eacute;').'lectricité et ' .html_entity_decode('&Eacute;').'nergie', 'en'=>'Electricity and Energy'];

                $subCategory123 = new Category();
                $subCategory123->description = ['fr' => 'Matériaux', 'en'=>'Materials'];

                $subCategory124 = new Category();
                $subCategory124->description = ['fr' => 'Matériaux second œuvre', 'en'=>'Second Work Materials'];

                $subCategory125 = new Category();
                $subCategory125->description = ['fr' => 'Composants électroniques', 'en'=>'Electronic components'];

            $subCategory13 = new Category();
            $subCategory13->description = ['fr' => 'Protection & Médical', 'en'=>'Protection & Medical'];

                $subCategory131 = new Category();
                $subCategory131->description = ['fr' => 'Matériel médical et paramédical', 'en'=>'Medical and paramedical equipment'];

                $subCategory132 = new Category();
                $subCategory132->description = ['fr' => 'Protection incendie', 'en'=>'Fire protection'];

                $subCategory133 = new Category();
                $subCategory133->description = ['fr' => html_entity_decode('&Eacute;').'quipement télésurveillance', 'en'=>'Remote monitoring'];

            $subCategory14 = new Category();
            $subCategory14->description = ['fr' => 'Bureau', 'en'=>'Office'];

                $subCategory141 = new Category();
                $subCategory141->description = ['fr' => 'Bureau & Fournitures', 'en'=>'Office & Supplies'];

            $subCategory15 = new Category();
            $subCategory15->description = ['fr' => 'Véhicules', 'en'=>'Vehicles'];

                $subCategory151 = new Category();
                $subCategory151->description = ['fr' => 'Auto Accessoires', 'en'=>'Auto Accessories'];

                $subCategory152 = new Category();
                $subCategory152->description = ['fr' => 'Lot automobile', 'en'=>'Lot automobile'];


        $parent1 = Category::find($rootCategory1->id);
            $parent1->appendNode($subCategory11);
                $parent11 = Category::find($subCategory11->id);
                    $parent11->appendNode($subCategory111);
                    $parent11->appendNode($subCategory112);
                    $parent11->appendNode($subCategory113);
                    $parent11->appendNode($subCategory114);
                    $parent11->appendNode($subCategory115);
                    $parent11->appendNode($subCategory116);
                    $parent11->appendNode($subCategory117);
                    $parent11->appendNode($subCategory118);
                    $parent11->appendNode($subCategory119);
            $parent1->appendNode($subCategory12);
                $parent12 = Category::find($subCategory12->id);
                    $parent12->appendNode($subCategory121);
                    $parent12->appendNode($subCategory122);
                    $parent12->appendNode($subCategory123);
                    $parent12->appendNode($subCategory124);
                    $parent12->appendNode($subCategory125);
            $parent1->appendNode($subCategory13);
                $parent13 = Category::find($subCategory13->id);
                    $parent13->appendNode($subCategory131);
                    $parent13->appendNode($subCategory132);
                    $parent13->appendNode($subCategory133);
            $parent1->appendNode($subCategory14);
                $parent14 = Category::find($subCategory14->id);
                    $parent14->appendNode($subCategory141);
            $parent1->appendNode($subCategory15);
                $parent15 = Category::find($subCategory15->id);
                    $parent15->appendNode($subCategory151);
                    $parent15->appendNode($subCategory152);


        /**
         *  ROOT 2
         */
        $rootCategory2 =new Category();
        $rootCategory2->description = ['fr' => 'Gadgets', 'en'=>'Gadgets'];
        $rootCategory2->saveAsRoot();

            $subCategory21 = new Category();
            $subCategory21->description = ['fr' => 'Artisanats Bois & Cuirs', 'en'=>'Crafts Wood & Leather'];

            $subCategory22 = new Category();
            $subCategory22->description = ['fr' => 'Articles de fêtes', 'en'=>'Party goods'];

            $subCategory23 = new Category();
            $subCategory23->description = ['fr' => 'Objets publicitaires', 'en'=>'Advertising items'];

            $subCategory24 = new Category();
            $subCategory24->description = ['fr' => 'Cadeaux d’affaires', 'en'=>'Business Gifts'];

            $subCategory25 = new Category();
            $subCategory25->description = ['fr' => 'Gadgets Insolites Ludiques', 'en'=>'Unusual Fun Gadgets'];

        $parent2 = Category::find($rootCategory2->id);
            $parent2->appendNode($subCategory21);
            $parent2->appendNode($subCategory22);
            $parent2->appendNode($subCategory23);
            $parent2->appendNode($subCategory24);
            $parent2->appendNode($subCategory25);


        /**
         *  ROOT 3
         */
        $rootCategory3 = new Category();
        $rootCategory3->description = ['fr' => 'Habillement', 'en'=>'Clothing'];
        $rootCategory3->saveAsRoot();

            $subCategory31 = new Category();
            $subCategory31->description = ['fr' => 'Vêtements', 'en'=>'Clothing'];

                $subCategory311 = new Category();
                $subCategory311->description = ['fr' => 'Tee-shirts, polos & Chemises', 'en'=>'T-shirts, polo shirts & shirts'];

                $subCategory312 = new Category();
                $subCategory312->description = ['fr' => 'Jeans & Pantalons', 'en'=>'Jeans & Trousers'];

                $subCategory313 = new Category();
                $subCategory313->description = ['fr' => 'Lingeries & Sous-vêtements', 'en'=>'Lingerie & Underwear'];

                $subCategory314 = new Category();
                $subCategory314->description = ['fr' => 'Manteaux & Vestes', 'en'=>'Coats & Jackets'];

                $subCategory315 = new Category();
                $subCategory315->description = ['fr' => 'Vêtements enfants', 'en'=>'Children\'s clothing'];

                $subCategory316 = new Category();
                $subCategory316->description = ['fr' => 'Vêtements hommes', 'en'=>'Men\'s clothing'];

                $subCategory317 = new Category();
                $subCategory317->description = ['fr' => 'Vêtements femmes', 'en'=>'Women clothing'];

            $subCategory32 = new Category();
            $subCategory32->description = ['fr' => 'Chaussures', 'en'=>'Shoes'];

                $subCategory321 = new Category();
                $subCategory321->description = ['fr' => 'Chaussures enfants & bébés', 'en'=>'Children\'s & babys shoes'];

                $subCategory322 = new Category();
                $subCategory322->description = ['fr' => 'Chaussures femmes', 'en'=>'Women shoes'];

                $subCategory323 = new Category();
                $subCategory323->description = ['fr' => 'Chaussures hommes', 'en'=>'Men\'s shoes'];

                $subCategory324 = new Category();
                $subCategory324->description = ['fr' => 'Autres chaussures', 'en'=>'Other shoes'];

                $subCategory325 = new Category();
                $subCategory325->description = ['fr' => 'Chaussettes', 'en'=>'Socks'];

            $subCategory33 = new Category();
            $subCategory33->description = ['fr' => 'Accessoires', 'en'=>'Accessories'];

                $subCategory331 = new Category();
                $subCategory331->description = ['fr' => 'Accessoires de Modes', 'en'=>'Fashion Accessories'];



        $parent3 = Category::find($rootCategory3->id);
            $parent3->appendNode($subCategory31);
                $parent31 = Category::find($subCategory31->id);
                    $parent31->appendNode($subCategory311);
                    $parent31->appendNode($subCategory312);
                    $parent31->appendNode($subCategory313);
                    $parent31->appendNode($subCategory314);
                    $parent31->appendNode($subCategory315);
                    $parent31->appendNode($subCategory316);
                    $parent31->appendNode($subCategory317);
            $parent3->appendNode($subCategory32);
                $parent32 = Category::find($subCategory32->id);
                    $parent32->appendNode($subCategory321);
                    $parent32->appendNode($subCategory322);
                    $parent32->appendNode($subCategory323);
                    $parent32->appendNode($subCategory324);
                    $parent32->appendNode($subCategory325);
            $parent3->appendNode($subCategory33);
                $parent33 = Category::find($subCategory33->id);
                    $parent33->appendNode($subCategory331);



        /**
         *  ROOT 4
         */
        $rootCategory4 = new Category();
        $rootCategory4->description = ['fr' => 'Alimentation', 'en'=>'Food'];
        $rootCategory4->saveAsRoot();

            $subCategory41 = new Category();
            $subCategory41->description = ['fr' => 'Culture Fruits et légumes', 'en'=>'Fruits and vegetables'];

            $subCategory42 = new Category();
            $subCategory42->description = ['fr' => 'Agriculture BIO', 'en'=>'Organic agriculture'];

            $subCategory43 = new Category();
            $subCategory43->description = ['fr' => 'Alimentation diététique', 'en'=>'Health food'];

            $subCategory44 = new Category();
            $subCategory44->description = ['fr' => 'Boissons', 'en'=>'Drinks'];

            $subCategory45 = new Category();
            $subCategory45->description = ['fr' => 'Autres Alimentations et Boissons', 'en'=>'Other Power Supplies'];

        $parent4 = Category::find($rootCategory4->id);
            $parent4->appendNode($subCategory41);
            $parent4->appendNode($subCategory42);
            $parent4->appendNode($subCategory43);
            $parent4->appendNode($subCategory44);
            $parent4->appendNode($subCategory45);


        /**
         *  ROOT 5
         */
        $rootCategory5 = new Category();
        $rootCategory5->description = ['fr' => 'Multimédia', 'en'=>'Multimedia'];
        $rootCategory5->saveAsRoot();

        $subCategory51 = new Category();
        $subCategory51->description = ['fr' => 'Image & son', 'en'=>'Picture & sound'];

        $subCategory52 = new Category();
        $subCategory52->description = ['fr' => 'Ordinateurs', 'en'=>'Computers'];

        $subCategory53 = new Category();
        $subCategory53->description = ['fr' => 'Composants Informatiques & Logiciels', 'en'=>'Computer Components & Software'];

        $subCategory54 = new Category();
        $subCategory54->description = ['fr' => 'Téléphonie', 'en'=>'Telephony'];

        $subCategory55 = new Category();
        $subCategory55->description = ['fr' => 'Equipements télécommunication', 'en'=>'Telecommunications'];

        $subCategory56 = new Category();
        $subCategory56->description = ['fr' => 'Autres multimédias', 'en'=>'Other multimedia'];

        $parent5 = Category::find($rootCategory5->id);
            $parent5->appendNode($subCategory51);
            $parent5->appendNode($subCategory52);
            $parent5->appendNode($subCategory53);
            $parent5->appendNode($subCategory54);
            $parent5->appendNode($subCategory55);
            $parent5->appendNode($subCategory56);


        /**
         *  ROOT 6
         */
        $rootCategory6 = new Category();
        $rootCategory6->description = ['fr' => 'Maison', 'en'=>'House'];
        $rootCategory6->saveAsRoot();

            $subCategory61 = new Category();
            $subCategory61->description = ['fr' => 'Malles & Coffres', 'en'=>'Trunks'];

            $subCategory62 = new Category();
            $subCategory62->description = ['fr' => 'Portefeuilles', 'en'=>'Wallets'];

            $subCategory63 = new Category();
            $subCategory63->description = ['fr' => 'Bagagerie & sacs', 'en'=>'Luggage & Bags'];

            $subCategory64 = new Category();
            $subCategory64->description = ['fr' => 'Electroménager', 'en'=>'Home appliance'];

            $subCategory65 = new Category();
            $subCategory65->description = ['fr' => 'Bien d’équipement', 'en'=>'Capital good'];

            $subCategory66 = new Category();
            $subCategory66->description = ['fr' => 'Appareils électriques', 'en'=>'Electrical appliances'];

            $subCategory67 = new Category();
            $subCategory67->description = ['fr' => 'Jardin : Mobilier, accessoires & produits', 'en'=>'Garden: Furniture, accessories & products'];

            $subCategory68 = new Category();
            $subCategory68->description = ['fr' => 'Ameublement', 'en'=>'Home Furnishings'];

            $subCategory69 = new Category();
            $subCategory69->description = ['fr' => 'Tissus', 'en'=>'Fabrics'];

        $parent6 = Category::find($rootCategory6->id);
            $parent6->appendNode($subCategory61);
            $parent6->appendNode($subCategory62);
            $parent6->appendNode($subCategory63);
            $parent6->appendNode($subCategory64);
            $parent6->appendNode($subCategory65);
            $parent6->appendNode($subCategory66);
            $parent6->appendNode($subCategory67);
            $parent6->appendNode($subCategory68);
            $parent6->appendNode($subCategory69);


        /**
         *  ROOT 7
         */
        $rootCategory7 = new Category();
        $rootCategory7->description = ['fr' => 'Autres', 'en'=>'Others'];
        $rootCategory7->saveAsRoot();

            $subCategory71 = new Category();
            $subCategory71->description = ['fr' => 'Jeux & Jouets', 'en'=>'Games toys'];

                $subCategory711 = new Category();
                $subCategory711->description = ['fr' => 'Consoles', 'en'=>'Video consoles'];

                $subCategory712 = new Category();
                $subCategory712->description = ['fr' => 'Accessoires pour Consoles', 'en'=>'Accessories for Consoles'];

                $subCategory713 = new Category();
                $subCategory713->description = ['fr' => 'Jouets filles', 'en'=>'Girls\' Toys'];

                $subCategory714 = new Category();
                $subCategory714->description = ['fr' => 'Jouets garçons', 'en'=>'Boys Toys'];

                $subCategory715 = new Category();
                $subCategory715->description = ['fr' => 'Jouets anciens', 'en'=>'Old toys'];

                $subCategory716 = new Category();
                $subCategory716->description = ['fr' => 'Autres jouets', 'en'=>'Other Toys'];

            $subCategory72 = new Category();
            $subCategory72->description = ['fr' => 'Accessoires beauté, bijouterie & horlogerie', 'en'=>'Beauty Accessories, Jewelery & Watchmaking'];

                $subCategory721 = new Category();
                $subCategory721->description = ['fr' => 'Beauté, Cosmétique & Maquillage', 'en'=>'Beauty, Cosmetics & Makeup'];

                $subCategory722 = new Category();
                $subCategory722->description = ['fr' => 'Soins, Bien-être & Diététique', 'en'=>'Care, Wellness & Dietetics'];

                $subCategory723 = new Category();
                $subCategory723->description = ['fr' => 'Bijoux & Joaillerie', 'en'=>'Jewelry & Watches'];

                $subCategory724 = new Category();
                $subCategory724->description = ['fr' => 'Montres & horloges', 'en'=>'Watches & clocks'];

                $subCategory725 = new Category();
                $subCategory725->description = ['fr' => 'Autres accessoires beauté', 'en'=>'Other Beauty Accessories'];

            $subCategory73 = new Category();
            $subCategory73->description = ['fr' => 'Loisirs', 'en'=>'Hobbies'];

                $subCategory731 = new Category();
                $subCategory731->description = ['fr' => 'Décorations & Objets créatifs', 'en'=>'Decorations & Creative Objects'];

                $subCategory732 = new Category();
                $subCategory732->description = ['fr' => 'Instruments de musique', 'en'=>'Musical instruments'];

                $subCategory733 = new Category();
                $subCategory733->description = ['fr' => 'Livres, BD & Revues', 'en'=>'Books, Comics & Magazines'];

                $subCategory734 = new Category();
                $subCategory734->description = ['fr' => 'Chasse & Pêche', 'en'=>'Hunting & fishing'];

                $subCategory735 = new Category();
                $subCategory735->description = ['fr' => 'Sport & Equipements sportifs', 'en'=>'Sport & sports facilities'];

                $subCategory736 = new Category();
                $subCategory736->description = ['fr' => 'Autres loisirs', 'en'=>'Other hobbies'];

            $subCategory74 = new Category();
            $subCategory74->description = ['fr' => 'Textiles & Cuirs', 'en'=>'Textiles & Leather Products'];

                $subCategory741 = new Category();
                $subCategory741->description = ['fr' => 'Corderie & ficellerie', 'en'=>'Cords and strings'];

                $subCategory742 = new Category();
                $subCategory742->description = ['fr' => 'Cuir', 'en'=>'Leather'];

                $subCategory743 = new Category();
                $subCategory743->description = ['fr' => 'Maroquinerie', 'en'=>'Leather goods'];

                $subCategory744 = new Category();
                $subCategory744->description = ['fr' => 'Tissus', 'en'=>'Fabrics'];

                $subCategory745 = new Category();
                $subCategory745->description = ['fr' => 'Autres Textile et Cuir', 'en'=>'Other Textile and Leather'];

            $subCategory75 = new Category();
            $subCategory75->description = ['fr' => 'Divers', 'en'=>'Various'];

                $subCategory751 = new Category();
                $subCategory751->description = ['fr' => 'Accessoires Auto & Moto', 'en'=>'Car Accessories'];

                $subCategory752 = new Category();
                $subCategory752->description = ['fr' => 'Puériculture', 'en'=>'Child care'];

                $subCategory753 = new Category();
                $subCategory753->description = ['fr' => 'Autres divers', 'en'=>'Other miscellaneous'];


        $parent7 = Category::find($rootCategory7->id);
            $parent7->appendNode($subCategory71);
                $parent71 = Category::find($subCategory71->id);
                    $parent71->appendNode($subCategory711);
                    $parent71->appendNode($subCategory712);
                    $parent71->appendNode($subCategory713);
                    $parent71->appendNode($subCategory714);
                    $parent71->appendNode($subCategory715);
                    $parent71->appendNode($subCategory716);
            $parent7->appendNode($subCategory72);
                $parent72 = Category::find($subCategory72->id);
                    $parent72->appendNode($subCategory721);
                    $parent72->appendNode($subCategory722);
                    $parent72->appendNode($subCategory723);
                    $parent72->appendNode($subCategory724);
                    $parent72->appendNode($subCategory725);
            $parent7->appendNode($subCategory73);
                $parent73 = Category::find($subCategory73->id);
                    $parent73->appendNode($subCategory731);
                    $parent73->appendNode($subCategory732);
                    $parent73->appendNode($subCategory733);
                    $parent73->appendNode($subCategory734);
                    $parent73->appendNode($subCategory735);
                    $parent73->appendNode($subCategory736);
            $parent7->appendNode($subCategory74);
                $parent74 = Category::find($subCategory74->id);
                    $parent74->appendNode($subCategory741);
                    $parent74->appendNode($subCategory742);
                    $parent74->appendNode($subCategory743);
                    $parent74->appendNode($subCategory744);
                    $parent74->appendNode($subCategory745);

            $parent7->appendNode($subCategory75);
                $parent75 = Category::find($subCategory75->id);
                    $parent75->appendNode($subCategory751);
                    $parent75->appendNode($subCategory752);
                    $parent75->appendNode($subCategory753);
    }
}
