<?php

return [
    // Admin theme
    'theme'           => env('ADMIN_THEME', 'candy-red'),

    // additional middleware for admin routes
    'middleware' => [],

    // Default login field to work with
    'login'           => [
        'username' => 'email',
    ],

    // User table and model
    'user'            => [
        'table' => 'users',
        'model' => \App\Models\User::class,
    ],

    // IP whitelist
    'whitelist'       => [
        'enabled'     => false,
        'fallback_ip' => env('WHITELIST_FALLBACK_IP', ''),
    ],

    // Show/hide copyrights text in footer
    'show_copyrights' => true,

    // Global date format for data tables
    'date_format'     => 'd.m.Y H:i',

    // Determine if project implements mail sending functionality
    'sends_emails'    => true,

    //Used for Testing
    'test'            => [
        'file_upload' => [
            'active' => true, //Should we run the test?
            'size'   => 50000 //Size in bytes that should be checked
        ],
        'export'      => [
            'active' => true //Should we run the test?
        ]
    ]
];
