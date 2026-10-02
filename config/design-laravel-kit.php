<?php

return [
    'id_prefix' => 'dlk',
    'assets_path' => 'vendor/design-laravel-kit',
    'spid' => [
        'providers' => [
            ['name' => 'Poste Italiane', 'url' => '/spid/login/poste'],
            ['name' => 'Aruba PEC', 'url' => '/spid/login/aruba'],
            ['name' => 'InfoCert', 'url' => '/spid/login/infocert'],
            ['name' => 'Sielte', 'url' => '/spid/login/sielte'],
            ['name' => 'Namirial', 'url' => '/spid/login/namirial'],
            ['name' => 'Intesi Group', 'url' => '/spid/login/intesi-group'],
            ['name' => 'Etna Hitech', 'url' => '/spid/login/etna-hitech'],
            ['name' => 'InfoCamere', 'url' => '/spid/login/infocamere'],
            ['name' => 'Lepida', 'url' => '/spid/login/lepida'],
            ['name' => 'Register', 'url' => '/spid/login/register'],
            ['name' => 'TeamSystem', 'url' => '/spid/login/teamsystem'],
            ['name' => 'TI Trust Technologies', 'url' => '/spid/login/ti-trust'],
        ],
    ],
];
