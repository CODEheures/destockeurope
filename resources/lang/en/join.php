<?php

return [

    'title' => 'Join US',

    'presentation' => '
    <p>
        '. env('LEGAL_COMPAGNY_NAME'). ' is etablished in France, '. env('LEGAL_COMPAGNY_ADDRESS') .'.
        #siret : . ' . env('LEGAL_COMPAGNY_SIRET')  . '
    </p>
    <p>
        '. env('LEGAL_COMPAGNY_PSEUDONAME'). ' does not offer a job offer or internship currently.
    </p>
    '

];
