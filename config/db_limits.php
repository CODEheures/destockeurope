<?php

return [

   'adverts' => [
       'minTitle' => 3,
       'maxTitle' => 50,
       'minDescription' => 30,
       'maxDescription' => 2000,
    ],

    'users' => [
        'minName' => 3,
        'maxPhone' => 30,
        'minCompagnyName' => 3,
        'maxCompagnyName' => 100,
        'minRegistrationNumber' => 1,
        'maxRegistrationNumber' => 30
    ],

    'messages' => [
        'minLength' => 30,
        'maxLength' => 2000,
    ]

];
