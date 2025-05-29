<?php

// config for Altwaireb/Countries
return [
    /*
* if you need to insert only activations countries set this to true.
*/
    'insert_activations_only' => false,

    /*
    * Countries
    */
    'countries' => [
        'activation' => [

            'default' => true,
            /*
            * You can create specific Countries by adding their code if it is iso2 or iso3.
            * e.x 'iso2' => ['US','GB'].
            * e.x 'iso3' => ['USA','GBR'].
            */
            'only' => [
                'iso2' => [],
                'iso3' => [],
            ],

            /*
            * You can exclude specific countries by adding their code if it is iso2 or iso3.
            * e.x 'iso2' => ['DJ','FJ'] .
            * e.x 'iso3' => ['DJI','FJI'] .
            */
            'except' => [
                'iso2' => [],
                'iso3' => [],
            ],
        ],
    ],

    /*
     * States
     */
    'states' => [
        'activation' => [
            'default' => true,
        ],
    ],

    /*
    * Cities
    */
    'cities' => [
        'activation' => [
            'default' => true,
        ],
    ],

    /*
    * length to insert data
    */
    'chunk_length' => 50,
];
