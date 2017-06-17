<?php

return [

    'geoIp' => [
        'uri' => [
            'mmdb' => 'http://geolite.maxmind.com/download/geoip/database/GeoLite2-City.mmdb.gz',
            'md5' => 'http://geolite.maxmind.com/download/geoip/database/GeoLite2-City.md5',
        ],

        'destination_directory' => storage_path('maxmind'),

        'routes' => [
            'prefix' => 'geoIp',
            'geoByIp' => [
                'uri' => 'geoByIp',
                'name' => 'codeheures.geoUtils.getGeoByIp',
                'middlewares' => ['auth']
            ],
            'geoLocByIp' => [
                'uri' => 'geoLocByIp',
                'name' => 'codeheures.geoUtils.getGeoLocByIp',
                'middlewares' => ['auth']
            ],
            'countryByIp' => [
                'uri' => 'countryByIp',
                'name' => 'codeheures.geoUtils.getCountryByIp',
                'middlewares' => ['auth']
            ],
            'refreshDb' => [
                'uri' => 'refreshDb',
                'name' => 'codeheures.geoUtils.refreshDb',
                'middlewares' => ['auth', 'isAdminUser']
            ]
        ],
    ],

    'ipTest' => '82.246.117.210',

    /**************************************************************************/
    //IMPORTANT: For DestockEurope: ADD available if resources/lang exist this
    //COMPLETE ALL CATEGORIE TRANSLATION. IF NOT: BUGGGGGG
    //NON REGIONNAL: en
    //REGIONNAL EX: en_US
    //*************************************************************************//
    'availableLocales' => [
        'fr',
        'en'
    ]
];
