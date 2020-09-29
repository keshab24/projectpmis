<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => '',
        'secret' => '',
    ],

    'mandrill' => [
        'secret' => '',
    ],

    'ses' => [
        'key' => '',
        'secret' => '',
        'region' => 'us-east-1',
    ],

    'stripe' => [
        'model' => PMIS\User::class,
        'key' => '',
        'secret' => '',
    ],

    'firebase' => [
        'api_key' => env('FIREBASE_API_KEY', ''), // Only used for JS integration
        'auth_domain' => env('FIREBASE_AUTH_DOMAIN', ''), // Only used for JS integration
        'database_url' => env('FIREBASE_API_DB_URL', ''), // Only used for JS integration
        'secret' => env('FIREBASE_DATABASE_SECRET', ''), // Only used for JS integration
        'storage_bucket' => env('FIREBASE_STORAGE_BUCKET', ''), // Only used for JS integration
    ],

    'fcm_notification' => [
        'authorization-key' => env('API_ACCESS_KEY',''),
    ],
];
