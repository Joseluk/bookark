<?php

namespace App\DataProvider;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class BooksrunDataProvider implements DataProviderInterface
{
    const PROVIDER_BOOKSRUN = 'BooksRun';

    private string $baseUrl = 'https://booksrun.com/api/v3/price/buy/';
    private string $apiKey = 'cvit7wqb1z4xns0x4hj7';
    private string $affiliateId = '18119';
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function fetchData(string $query): array
    {
        $url = $this->buildUrl($query); // Query es un ISBN siempre para Booksrun
        $response = $this->client->request('GET', $url);
        if ($response->getStatusCode() != 200) {
            throw new \Exception("Error: " . $response->getContent());
        }

        $data = json_decode($response->getContent(), true);

        // Comprobamos si tenemos el status 'success' y si existen ofertas de 'booksrun' y 'new'
        if (isset($data['result']['status']) && $data['result']['status'] === 'success' &&
            isset($data['result']['offers']['booksrun']['new']) && $data['result']['offers']['booksrun']['new'] !== 'none') {

            $booksrunOffer = $data['result']['offers']['booksrun']['new'];

            // Retornamos la información necesaria en el formato esperado
            return [
                'precio' => $booksrunOffer['price'],
                'buyUrl' => $booksrunOffer['cart_url'],
                'provider' => self::PROVIDER_BOOKSRUN,
            ];
        }

        return [
            'precio' => null,
            'buyUrl' => null,
            'provider' => self::PROVIDER_BOOKSRUN,
        ];
    }

    private function buildUrl(string $isbn): string
    {
        return $this->baseUrl . $isbn . '?key=' . $this->apiKey . '&afk=' . $this->affiliateId;
    }

}
