<?php
namespace App;

use GuzzleHttp\Client;

class UserClickhouse {
    private $client;

    public function __construct() {
        $this->client = new Client([
            'base_uri' => 'http://clickhouse:8123/',
            'timeout'  => 2.0,
        ]);
    }

    public function execute(string $sql): string {
        $response = $this->client->post('', [
            'body' => $sql
        ]);
        return $response->getBody()->getContents();
    }

    public function insertUser(int $id, string $name, int $age): void {
        $this->execute("INSERT INTO users (id, name, age) VALUES ($id, '$name', $age)");
    }
}