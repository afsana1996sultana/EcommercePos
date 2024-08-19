<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    // 'facebook' => [
    //     'client_id' => '398276169318026',
    //     'client_secret' => '6db5371992a47c374c099d8c3d0615f0',
    //     'redirect' => 'https://beautyproductsbd.com/login/facebook/callback',
    // ],
    // beautyproducts
    
    'facebook' => [
        'client_id' => '344554278471041',
        'client_secret' => '1e8e53dd292e7bb1dd6f8bee99ec18cd',
        'redirect' => 'https://beautyproductsbd.com/login/facebook/callback',
    ],

    'google' => [
        'client_id' => '997617597848-8r0a006fqe4j2pcg8mm1ilcitrajmg2r.apps.googleusercontent.com',
        'client_secret' => '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',
        'redirect' => 'https://beautyproductsbd.com/login/google/callback',
    ],

];