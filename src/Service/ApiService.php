<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;

class ApiService
{
    private $apiKey;
    private $httpClient;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->httpClient = HttpClient::create();
    }

    // les méthodes pour interagir avec l'API
}