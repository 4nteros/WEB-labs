<?php
namespace App;

use GuzzleHttp\Client;

class UserElastic {
    private $client;

    public function __construct() {
        $this->client = new Client([
            'base_uri' => 'http://elasticsearch:9200/',
            'timeout'  => 2.0,
        ]);
    }

    public function indexUser(string $id, array $data): string {
        $response = $this->client->put("users/_doc/$id", [
            'json' => $data
        ]);
        return $response->getBody()->getContents();
    }

    public function searchUser(string $name): array {
        try {
            $response = $this->client->get("users/_search", [
                'json' => [
                    'query' => [
                        'match' => ['name' => $name]
                    ]
                ]
            ]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}