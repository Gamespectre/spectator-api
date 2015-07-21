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
        'key'    => '',
        'secret' => '',
        'region' => 'us-east-1',
    ],

    'stripe' => [
        'model'  => Spectator\User::class,
        'key'    => '',
        'secret' => '',
    ],

    'youtube' => [
        'redirect' => getenv('GOOGLE_CALLBACK'),
        'client_id' => getenv('GOOGLE_OAUTH_ID'),
        'client_secret' => getenv('GOOGLE_OAUTH_SECRET'),
    ]

];
