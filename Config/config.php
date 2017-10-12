<?php

return [

    //module name
    'name'  => 'Admin',

    'theme' => env('ADMIN_THEME', 'candy-red'),

    //default login field to work with
    'login' => [
        'username' => 'email'
    ]
];
