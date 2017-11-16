<?php

return [
    // Admin theme
    'theme' => env('ADMIN_THEME', 'candy-red'),

    // Default login field to work with
    'login' => [
        'username' => 'email'
    ],

    // User table and model
    'user' => [
        'table' => 'users',
        'model' => \App\User::class
    ]
];
