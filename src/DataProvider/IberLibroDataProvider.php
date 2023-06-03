<?php

namespace App\DataProvider;

use App\Utils\WebScraper;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

class IberLibroDataProvider implements DataProviderInterface
{
    private const PROVIDER_NAME = "IberLibro";
    private const API_BASE_URL = 'https://www.iberlibro.com/servlet/SearchResults?kn=';
    private Client $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client();
    }

    /**
     * @throws Exception
     */
    public function fetchData(string $query): array
    {
        $url = $this->buildUrl($query);

        try {
            $response = $this->httpClient->get($url)->getBody()->getContents();
        } catch (RequestException $e) {
            throw new Exception("Error al obtener los datos de " . self::PROVIDER_NAME);
        } catch (GuzzleException $e) {
            throw new Exception("Error: " . $e);
        }

        $htmlParser = new WebScraper($response); // Instanciamos nuestro WebScraper
        $data = $htmlParser->extractSingleBookDataIberLibro();

        return [
            'precio' => $data['precio'] ?? null,
            'buyUrl' => $data['buyUrl'] ?? null,
            'provider' => $data['provider']
        ];
    }

    private function buildUrl(string $isbn): string
    {
        return self::API_BASE_URL . $isbn;
    }
}

