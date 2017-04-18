<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lat = 47.3526;
        $lng = 0.6702587142943912;


        $user1 = new User();
        $user1->name = 'client';
        $user1->email = 'client@destockeurope.com';
        $user1->phone = '06.87.34.06.83';
        $user1->password = bcrypt('123456');
        $user1->locale = env('DEFAULT_LOCALE');
        $user1->currency = env('DEFAULT_CURRENCY');
        $user1->latitude = $lat;
        $user1->longitude = $lng;
        $user1->setRememberToken(Str::random(60));
        $user1->confirmed = true;
        $user1->save();

        $user2 = new User();
        $user2->name = 'vendeur';
        $user2->email = 'vendeur@destockeurope.com';
        $user2->password = bcrypt('123456');
        $user2->locale = env('DEFAULT_LOCALE');
        $user2->currency = env('DEFAULT_CURRENCY');
        $user2->latitude = $lat;
        $user2->longitude = $lng;
        $user2->setRememberToken(Str::random(60));
        $user2->confirmed = true;
        $user2->save();

        $user3 = new User();
        $user3->name = 'admin';
        $user3->email = 'admin@destockeurope.com';
        $user3->password = bcrypt('lbjfdsgdestock!');
        $user3->locale = env('DEFAULT_LOCALE');
        $user3->currency = env('DEFAULT_CURRENCY');
        $user3->latitude = $lat;
        $user3->longitude = $lng;
        $user3->setRememberToken(Str::random(60));
        $user3->confirmed = true;
        $user3->role='admin';
        $user3->save();

        $user4 = new User();
        $user4->name = 'delegation';
        $user4->email = 'delegation@destockeurope.com';
        $user4->password = bcrypt('123456');
        $user4->locale = env('DEFAULT_LOCALE');
        $user4->currency = env('DEFAULT_CURRENCY');
        $user4->compagnyName= env('DEFAULT_DELEGATE');
        $user4->registrationNumber = '123456789A123';
        $user4->latitude = 47.3478;
        $user4->longitude = 0.6486454740523868;
        $user4->geoloc= '[{"address_components":[{"long_name":"32","short_name":"32","types":["street_number"]},{"long_name":"Rue Gutenberg","short_name":"Rue Gutenberg","types":["route"]},{"long_name":"Joué-lès-Tours","short_name":"Joué-lès-Tours","types":["locality","political"]},{"long_name":"Indre-et-Loire","short_name":"Indre-et-Loire","types":["administrative_area_level_2","political"]},{"long_name":"Centre-Val de Loire","short_name":"Centre-Val de Loire","types":["administrative_area_level_1","political"]},{"long_name":"France","short_name":"FR","types":["country","political"]},{"long_name":"37300","short_name":"37300","types":["postal_code"]}],"formatted_address":"32 Rue Gutenberg, 37300 Joué-lès-Tours, France","geometry":{"location":{"lat":47.3477464,"lng":0.6489845999999488},"location_type":"ROOFTOP","viewport":{"south":47.3463974197085,"west":0.647635619708467,"north":47.34909538029149,"east":0.6503335802915444}},"place_id":"ChIJDXcjat4p_UcRrdgENsC3RTM","types":["street_address"]},{"address_components":[{"long_name":"Joué-lès-Tours","short_name":"Joué-lès-Tours","types":["locality","political"]},{"long_name":"Indre-et-Loire","short_name":"Indre-et-Loire","types":["administrative_area_level_2","political"]},{"long_name":"Centre-Val de Loire","short_name":"Centre-Val de Loire","types":["administrative_area_level_1","political"]},{"long_name":"France","short_name":"FR","types":["country","political"]}],"formatted_address":"Joué-lès-Tours, France","geometry":{"bounds":{"south":47.299816,"west":0.6094590000000153,"north":47.375242,"east":0.6992960000000039},"location":{"lat":47.351861,"lng":0.661309899999992},"location_type":"APPROXIMATE","viewport":{"south":47.299816,"west":0.6094590000000153,"north":47.375242,"east":0.6992960000000039}},"place_id":"ChIJcWDGIhfW_EcRyR4q_XnK6Us","types":["locality","political"]},{"address_components":[{"long_name":"37300","short_name":"37300","types":["postal_code"]},{"long_name":"Joué-lès-Tours","short_name":"Joué-lès-Tours","types":["locality","political"]},{"long_name":"Indre-et-Loire","short_name":"Indre-et-Loire","types":["administrative_area_level_2","political"]},{"long_name":"Centre-Val de Loire","short_name":"Centre-Val de Loire","types":["administrative_area_level_1","political"]},{"long_name":"France","short_name":"FR","types":["country","political"]}],"formatted_address":"37300 Joué-lès-Tours, France","geometry":{"bounds":{"south":47.2998031,"west":0.6092559999999594,"north":47.37539599999999,"east":0.6992147999999361},"location":{"lat":47.33938,"lng":0.6630261999999902},"location_type":"APPROXIMATE","viewport":{"south":47.2998031,"west":0.6092559999999594,"north":47.37539599999999,"east":0.6992147999999361}},"place_id":"ChIJx-sfRijW_EcRUAIKiNrIDRw","types":["postal_code"]},{"address_components":[{"long_name":"Indre-et-Loire","short_name":"Indre-et-Loire","types":["administrative_area_level_2","political"]},{"long_name":"Centre-Val de Loire","short_name":"Centre-Val de Loire","types":["administrative_area_level_1","political"]},{"long_name":"France","short_name":"FR","types":["country","political"]}],"formatted_address":"Indre-et-Loire, France","geometry":{"bounds":{"south":46.736714,"west":0.05273690000001352,"north":47.7098679,"east":1.3660489999999754},"location":{"lat":47.28949249999999,"lng":0.8160970000000134},"location_type":"APPROXIMATE","viewport":{"south":46.736714,"west":0.05273690000001352,"north":47.7098679,"east":1.3660489999999754}},"place_id":"ChIJE3AWTDjZ_EcR0CczBdfIDQM","types":["administrative_area_level_2","political"]},{"address_components":[{"long_name":"Centre-Val de Loire","short_name":"Centre-Val de Loire","types":["administrative_area_level_1","political"]},{"long_name":"France","short_name":"FR","types":["country","political"]}],"formatted_address":"Centre-Val de Loire, France","geometry":{"bounds":{"south":46.3469059,"west":0.05273690000001352,"north":48.941029,"east":3.1284098999999514},"location":{"lat":47.7515686,"lng":1.6750630999999885},"location_type":"APPROXIMATE","viewport":{"south":46.3469059,"west":0.05273690000001352,"north":48.941029,"east":3.1284098999999514}},"place_id":"ChIJiV0INnu55EcRMCUzBdfIDQE","types":["administrative_area_level_1","political"]},{"address_components":[{"long_name":"France","short_name":"FR","types":["country","political"]}],"formatted_address":"France","geometry":{"bounds":{"south":41.3253001,"west":-5.559099999999944,"north":51.1241999,"east":9.662499900000057},"location":{"lat":46.227638,"lng":2.213749000000007},"location_type":"APPROXIMATE","viewport":{"south":41.342778,"west":-5.142257900000004,"north":51.0891285,"east":9.55979339999999}},"place_id":"ChIJMVd4MymgVA0R99lHx5Y__Ws","types":["country","political"]}]';
        $user4->setRememberToken(Str::random(60));
        $user4->confirmed = true;
        $user4->role='delegation';
        $user4->save();
    }
}
