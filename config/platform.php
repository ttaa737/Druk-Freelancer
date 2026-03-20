<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Platform Identity
    |--------------------------------------------------------------------------
    */
    'name'        => env('APP_NAME', 'Druk Freelancer'),
    'tagline'     => 'Bhutan\'s Digital Marketplace for Talent',
    'country'     => 'Bhutan',
    'currency'    => 'Nu',
    'currency_code' => 'BTN',
    'locale'      => 'en',
    'timezone'    => 'Asia/Thimphu',

    /*
    |--------------------------------------------------------------------------
    | Financial Settings
    |--------------------------------------------------------------------------
    */
    'service_fee_percent'  => (float) env('PLATFORM_SERVICE_FEE_PERCENT', 10),
    'min_withdrawal'       => (float) env('PLATFORM_MIN_WITHDRAWAL', 500),
    'min_deposit'          => (float) env('PLATFORM_MIN_DEPOSIT', 100),
    'max_deposit'          => (float) env('PLATFORM_MAX_DEPOSIT', 100000),

    /*
    |--------------------------------------------------------------------------
    | Bhutanese Payment Providers
    |--------------------------------------------------------------------------
    */
    'payment_providers' => [
        'mbob' => [
            'name'    => 'mBoB',
            'label'   => 'Bank of Bhutan Mobile Banking',
            'api_key' => env('MBOB_API_KEY'),
            'api_url' => env('MBOB_API_URL', 'https://api.mbob.bt'),
            'active'  => true,
        ],
        'mpay' => [
            'name'    => 'mPay',
            'label'   => 'Bhutan National Bank mPay',
            'api_key' => env('MPAY_API_KEY'),
            'api_url' => env('MPAY_API_URL', 'https://api.mpay.com.bt'),
            'active'  => true,
        ],
        'tpay' => [
            'name'    => 'TPay',
            'label'   => 'T Bank TPay',
            'api_key' => env('TPAY_API_KEY'),
            'api_url' => env('TPAY_API_URL', 'https://api.tpay.bt'),
            'active'  => true,
        ],
        'epay' => [
            'name'    => 'ePay',
            'label'   => 'Druk PNB ePay',
            'api_key' => env('EPAY_API_KEY'),
            'api_url' => env('EPAY_API_URL', 'https://api.epay.bt'),
            'active'  => true,
        ],
        'drukpay' => [
            'name'    => 'DrukPay',
            'label'   => 'DrukPay Gateway',
            'api_key' => env('DRUKPAY_API_KEY'),
            'api_url' => env('DRUKPAY_API_URL', 'https://api.drukpay.bt'),
            'active'  => true,
        ],
        'dkpay' => [
            'name'    => 'DK Pay',
            'label'   => 'Druk Karma Pay',
            'api_key' => env('DKPAY_API_KEY'),
            'api_url' => env('DKPAY_API_URL', 'https://api.dkpay.bt'),
            'active'  => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | OTP Settings
    |--------------------------------------------------------------------------
    */
    'otp_length'         => 6,
    'otp_expires_minutes' => 10,
    'otp_max_attempts'   => 5,

    /*
    |--------------------------------------------------------------------------
    | Proposal Limits
    |--------------------------------------------------------------------------
    */
    'max_proposals_per_job' => 50,
    'max_payment_methods'   => 5,

    /*
    |--------------------------------------------------------------------------
    | File Upload Limits (KB)
    |--------------------------------------------------------------------------
    */
    'max_avatar_size'       => 2048,
    'max_attachment_size'   => 10240,
    'max_document_size'     => 5120,
];
