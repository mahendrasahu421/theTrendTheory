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

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
    'cloudinary' => [
        'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
        'api_key' => env('CLOUDINARY_API_KEY'),
        'api_secret' => env('CLOUDINARY_API_SECRET'),
        'sync_upload' => env('CLOUDINARY_SYNC_UPLOAD', false),
    ],
    'razorpay' => [
        'key_id' => env('RAZORPAY_KEY_ID', 'rzp_test_TRBFsbQ4XURTge'),
        'key_secret' => env('RAZORPAY_KEY_SECRET', 'BtT5h3mgE57NChkD45u2xHPI'),
    ],
    'phonepe' => [
        'merchant_id'    => env('PHONEPE_MERCHANT_ID', 'PGTESTPAYUAT86'),
        'salt_key'       => env('PHONEPE_SALT_KEY', '96434309-7796-489d-8924-ab34988a6161'),
        'salt_index'     => env('PHONEPE_SALT_INDEX', 1),
        'client_id'      => env('PHONEPE_CLIENT_ID', 'PGTESTPAYUAT86'),
        'client_secret'  => env('PHONEPE_CLIENT_SECRET', '96434309-7796-489d-8924-ab34988a6161'),
        'client_version' => env('PHONEPE_CLIENT_VERSION', '1'),
        'env'            => env('PHONEPE_ENV', 'UAT'), // 'UAT' or 'PRODUCTION'
    ],
];
