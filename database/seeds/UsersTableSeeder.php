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
        $user3->password = bcrypt('123456');
        $user3->locale = env('DEFAULT_LOCALE');
        $user3->currency = env('DEFAULT_CURRENCY');
        $user3->latitude = $lat;
        $user3->longitude = $lng;
        $user3->setRememberToken(Str::random(60));
        $user3->confirmed = true;
        $user3->role=User::ROLES[\App\User::ROLE_ADMIN];
        $user3->save();

        $user4 = new User();
        $user4->name = 'fournisseur';
        $user4->email = 'fournisseur@destockeurope.com';
        $user4->password = bcrypt('123456');
        $user4->locale = env('DEFAULT_LOCALE');
        $user4->currency = env('DEFAULT_CURRENCY');
        $user4->compagnyName= 'PF Concept';
        $user4->registrationNumber = '123456789A123';
        $user4->latitude = 47.3478;
        $user4->longitude = 0.6486454740523868;
        $user4->geoloc= '[{"address_components":[{"long_name":"11","short_name":"11","types":["street_number"]},{"long_name":"Allée des Sarments","short_name":"Allée des Sarments","types":["route"]},{"long_name":"Croissy-Beaubourg","short_name":"Croissy-Beaubourg","types":["locality","political"]},{"long_name":"Seine-et-Marne","short_name":"Seine-et-Marne","types":["administrative_area_level_2","political"]},{"long_name":"Île-de-France","short_name":"Île-de-France","types":["administrative_area_level_1","political"]},{"long_name":"France","short_name":"FR","types":["country","political"]},{"long_name":"77183","short_name":"77183","types":["postal_code"]}],"formatted_address":"11 Allée des Sarments, 77183 Croissy-Beaubourg, France","geometry":{"location":{"lat":48.812933,"lng":2.637906799999996},"location_type":"ROOFTOP","viewport":{"south":48.8115840197085,"west":2.636557819708514,"north":48.8142819802915,"east":2.639255780291478}},"place_id":"ChIJH4qJwn4F5kcRqhKY0n09zZw","types":["street_address"]},{"address_components":[{"long_name":"Croissy-Beaubourg","short_name":"Croissy-Beaubourg","types":["locality","political"]},{"long_name":"Seine-et-Marne","short_name":"Seine-et-Marne","types":["administrative_area_level_2","political"]},{"long_name":"Île-de-France","short_name":"Île-de-France","types":["administrative_area_level_1","political"]},{"long_name":"France","short_name":"FR","types":["country","political"]}],"formatted_address":"Croissy-Beaubourg, France","geometry":{"bounds":{"south":48.800273,"west":2.62549690000003,"north":48.83243909999999,"east":2.6895130000000336},"location":{"lat":48.8279179,"lng":2.659396000000015},"location_type":"APPROXIMATE","viewport":{"south":48.800273,"west":2.62549690000003,"north":48.83243909999999,"east":2.6895130000000336}},"place_id":"ChIJZ6-h2wwF5kcRAF2MaMOCCwQ","types":["locality","political"]},{"address_components":[{"long_name":"77183","short_name":"77183","types":["postal_code"]},{"long_name":"Croissy-Beaubourg","short_name":"Croissy-Beaubourg","types":["locality","political"]},{"long_name":"Seine-et-Marne","short_name":"Seine-et-Marne","types":["administrative_area_level_2","political"]},{"long_name":"Île-de-France","short_name":"Île-de-France","types":["administrative_area_level_1","political"]},{"long_name":"France","short_name":"FR","types":["country","political"]}],"formatted_address":"77183 Croissy-Beaubourg, France","geometry":{"bounds":{"south":48.8001677,"west":2.6253507000000127,"north":48.832444,"east":2.689595700000041},"location":{"lat":48.814117,"lng":2.661959300000035},"location_type":"APPROXIMATE","viewport":{"south":48.8001677,"west":2.6253507000000127,"north":48.832444,"east":2.689595700000041}},"place_id":"ChIJZ6-h2wwF5kcRsFTY4caCCxw","types":["postal_code"]},{"address_components":[{"long_name":"Seine-et-Marne","short_name":"Seine-et-Marne","types":["administrative_area_level_2","political"]},{"long_name":"Île-de-France","short_name":"Île-de-France","types":["administrative_area_level_1","political"]},{"long_name":"France","short_name":"FR","types":["country","political"]}],"formatted_address":"Seine-et-Marne, France","geometry":{"bounds":{"south":48.1200811,"west":2.392326099999991,"north":49.1178979,"east":3.5590068999999858},"location":{"lat":48.841082,"lng":2.999366000000009},"location_type":"APPROXIMATE","viewport":{"south":48.1200811,"west":2.392326099999991,"north":49.1178979,"east":3.5590068999999858}},"place_id":"ChIJa5Z5nApS70cRUCqLaMOCCwM","types":["administrative_area_level_2","political"]},{"address_components":[{"long_name":"Île-de-France","short_name":"Île-de-France","types":["administrative_area_level_1","political"]},{"long_name":"France","short_name":"FR","types":["country","political"]}],"formatted_address":"Île-de-France, France","geometry":{"bounds":{"south":48.1200811,"west":1.4461699999999382,"north":49.241504,"east":3.5590068999999858},"location":{"lat":48.8499198,"lng":2.637041100000033},"location_type":"APPROXIMATE","viewport":{"south":48.1200811,"west":1.4461699999999382,"north":49.241504,"east":3.5590068999999858}},"place_id":"ChIJF4ymA8Th5UcRcCWLaMOCCwE","types":["administrative_area_level_1","political"]},{"address_components":[{"long_name":"Paris Metropolitan Area","short_name":"Paris Metropolitan Area","types":["political"]},{"long_name":"France","short_name":"FR","types":["country","political"]}],"formatted_address":"Paris Metropolitan Area, France","geometry":{"bounds":{"south":48.065382,"west":1.3557488999999805,"north":49.44956,"east":3.5590339000000313},"location":{"lat":48.8575712,"lng":2.2771715000000086},"location_type":"APPROXIMATE","viewport":{"south":48.065382,"west":1.3557488999999805,"north":49.44956,"east":3.5590339000000313}},"place_id":"ChIJW36m20gL5kcRiMnAEpHgyq8","types":["political"]},{"address_components":[{"long_name":"France","short_name":"FR","types":["country","political"]}],"formatted_address":"France","geometry":{"bounds":{"south":41.3253001,"west":-5.559099999999944,"north":51.1241999,"east":9.662499900000057},"location":{"lat":46.227638,"lng":2.213749000000007},"location_type":"APPROXIMATE","viewport":{"south":41.342778,"west":-5.142257900000004,"north":51.0891628,"east":9.55979339999999}},"place_id":"ChIJMVd4MymgVA0R99lHx5Y__Ws","types":["country","political"]}]';
        $user4->setRememberToken(Str::random(60));
        $user4->confirmed = true;
        $user4->role=User::ROLES[User::ROLE_SUPPLIER];
        $user4->save();

        $user5 = new User();
        $user5->name = 'valideur';
        $user5->email = 'valideur@destockeurope.com';
        $user5->password = bcrypt('123456');
        $user5->locale = env('DEFAULT_LOCALE');
        $user5->currency = env('DEFAULT_CURRENCY');
        $user5->setRememberToken(Str::random(60));
        $user5->confirmed = true;
        $user5->role=User::ROLES[User::ROLE_VALIDATOR];
        $user5->save();

        $user6 = new User();
        $user6->name = 'comptable';
        $user6->email = 'comptable@destockeurope.com';
        $user6->password = bcrypt('123456');
        $user6->locale = env('DEFAULT_LOCALE');
        $user6->currency = env('DEFAULT_CURRENCY');
        $user6->setRememberToken(Str::random(60));
        $user6->confirmed = true;
        $user6->role=User::ROLES[User::ROLE_ACCOUNTANT];
        $user6->save();

        $user7 = new User();
        $user7->name = 'Intermédiaire';
        $user7->email = 'intermediaire@destockeurope.com';
        $user7->password = bcrypt('123456');
        $user7->locale = env('DEFAULT_LOCALE');
        $user7->currency = env('DEFAULT_CURRENCY');
        $user7->setRememberToken(Str::random(60));
        $user7->confirmed = true;
        $user7->role=User::ROLES[User::ROLE_INTERMEDIARY];
        $user7->save();
    }
}
