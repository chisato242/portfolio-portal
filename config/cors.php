<?php

return [
    'paths' => ['api/*', 'storage/*'],

    'allowed_methods' => ['*'],

    // Flutter Web の開発サーバー(flutter run -d chrome)や本番ドメインを追加してください
   'allowed_origins' => [
    'https://portfolio-portal-web.web.app',
    'https://portfolio-portal-web.firebaseapp.com',
],

    // allowed_origins は完全一致のみなので、ポート番号が可変なローカル開発用は正規表現で許可
    'allowed_origins_patterns' => [
        '#^http://localhost:\d+$#',
        '#^http://127\.0\.0\.1:\d+$#',
    ],

    
    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,
];
