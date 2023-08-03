<?php

namespace William\SevencooksTestTask\ApiClient;

use GuzzleHttp\Client;

abstract class AbstractApiClient
{
    protected Client $client;

    public function __construct(string $baseUri)
    {
        $this->client = new Client([
            'base_uri' => $baseUri,
            'timeout' => 20.0
        ]);
    }

    abstract function getStarships(string $uri): array;
    abstract function getPilotInfo(string $pilotUri): array;
}