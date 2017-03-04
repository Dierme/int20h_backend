<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
    'http-client' => GuzzleHttp\Client::class,
    'dictionaryClass' => common\components\Dictionary::class,
    'dictionary' => [
        'apiKey' => 'dict.1.1.20170226T193908Z.fb73e68527f24edd.032c760fff0bbb517530c61ab0cf6b527695957d',
        'baseUrl' => 'https://dictionary.yandex.net/api/v1/dicservice.json/lookup'
    ]
];
