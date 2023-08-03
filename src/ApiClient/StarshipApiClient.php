<?php

namespace William\SevencooksTestTask\ApiClient;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class StarshipApiClient extends AbstractApiClient
{
    /**
     * @throws GuzzleException
     */
    public function getStarships(string $uri, int $limit = 10): array
    {
        $starships = [];
        $page = 1;
        $totalStarships = 0;

        do {
            $uriWithPagination = "$uri?page=$page&limit=$limit";
            $response = $this->client->get($uriWithPagination);
            $data = json_decode($response->getBody(), true);

            $starships = array_merge($starships, $data['results']);
            $totalStarships += count($data['results']);
            $page++;

        } while (!empty($data['next']) && $totalStarships < $limit);

        return array_slice($starships, 0, $limit, true);
    }

    /**
     * @throws GuzzleException
     */
    public function getPilotInfo(string $pilotUri): array
    {
        $client = new Client();
        $response = $client->get($pilotUri);
        return json_decode($response->getBody(), true);
    }
}