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

function getTmdbId($title, $year) {
    global $client;

    try {
        $response = $client->get('search/movie', [
            'query' => [
                'query' => $title,
                'year' => $year
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        if (!empty($data['results'])) {
            return $data['results'][0]['id'];
        }
    } catch (Exception $e) {
        error_log('Error fetching TMDb ID: ' . $e->getMessage());
    }

    return null;
}

function getPosterPath($tmdbId) {
    global $client;

    if (!$tmdbId) {
        return null;
    }

    try {
        $response = $client->get("movie/{$tmdbId}");
        $data = json_decode($response->getBody(), true);

        return $data['poster_path'] ?? null;
    } catch (Exception $e) {
        error_log('Error fetching poster path: ' . $e->getMessage());
    }

    return null;
}