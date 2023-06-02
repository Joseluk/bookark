<?php

namespace App\DataProvider;

use App\Entity\Book;
use App\Entity\Provider;
use App\Utils\WebScraper;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

class TodosTusLibrosDataProvider
{
    private const BASE_URL = 'https://www.todostuslibros.com/';
    private const WEBSCRAP_BUSQUEDA = 'busquedas?keyword=';
    private const WEBSCRAP_MAS_VENDIDOS = 'mas_vendidos';

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
            throw new Exception("Error al obtener los datos de TodosTusLibros");
        } catch (GuzzleException $e) {
            throw new Exception("Error: " . $e);
        }

        $htmlParser = new WebScraper($response); // Instanciamos nuestro WebScraper

        // Si el elemento 'id="search-filters"' existe, significa que hay más de un resultado, esto conlleva un proceso diferente de Scraping.
        return $htmlParser->elementWithIdExists('search-filters') ? $htmlParser->extractMultipleBooksDataTodosTusLibros() : $htmlParser->extractSingleBookDataTodosTusLibros();
    }

    public function fetchPopularData(): array
    {
        $url = $this->buildPopularUrl();

        try {
            $response = $this->httpClient->get($url)->getBody()->getContents();
        } catch (RequestException $e) {
            throw new Exception("Error al obtener los datos de TodosTusLibros");
        } catch (GuzzleException $e) {
            throw new Exception("Error: " . $e);
        }

        $htmlParser = new WebScraper($response); // Instanciamos nuestro WebScraper

        return $htmlParser->extractMultipleBooksDataTodosTusLibros();
    }

    private function buildUrl(string $query): string
    {
        return self::BASE_URL . self::WEBSCRAP_BUSQUEDA . $query;
    }

    private function buildPopularUrl(): string
    {
        return self::BASE_URL . self::WEBSCRAP_MAS_VENDIDOS;
    }
}

