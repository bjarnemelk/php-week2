<?php

require_once __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/../config/tmdb_config.php';

$client = new \GuzzleHttp\Client([
    'base_uri' => $config['base_url'],
    'headers' => [
        'Authorization' => 'Bearer ' . $config['token'],
        'Accept' => 'application/json'
    ]
]);