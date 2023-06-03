<?php

namespace App\Factory;

use App\DataProvider\BooksrunDataProvider;
use App\DataProvider\IberLibroDataProvider;
use App\DataProvider\TodosTusLibrosDataProvider;
use App\Entity\Book;
use App\Entity\Provider;
use App\Utils\BookFormater;

class BookFactory
{
    private BooksrunDataProvider $booksrunDataProvider;
    private TodosTusLibrosDataProvider $todosTusLibrosDataProvider;
    private IberLibroDataProvider $iberLibroDataProvider;
    private BookFormater $bookFormater;

    public function __construct(
        BooksrunDataProvider       $booksrunDataProvider,
        TodosTusLibrosDataProvider $todosTusLibrosDataProvider,
        IberLibroDataProvider      $iberLibroDataProvider,
        BookFormater               $bookFormater
    )
    {
        $this->booksrunDataProvider = $booksrunDataProvider;
        $this->todosTusLibrosDataProvider = $todosTusLibrosDataProvider;
        $this->iberLibroDataProvider = $iberLibroDataProvider;
        $this->bookFormater = $bookFormater;
    }

    public function queryBooks(string $query): array
    {
        return $this->handleBookData(function() use ($query) {
            return $this->todosTusLibrosDataProvider->fetchData($query);
        });
    }

    public function getPopularBooks(): array
    {
        return $this->handleBookData(function() {
            return $this->todosTusLibrosDataProvider->fetchPopularData();
        });
    }

    private function handleBookData(callable $dataFetcher): array
    {
        $resultBookData = [];
        try {
            $resultBookData = $dataFetcher(); // Llamamos a la función pasada por parámetro
            if(!empty($resultBookData)) {
                if(isset($resultBookData[0])) { // Si hemos obtenido más de 1 libro... (el array es multidimensional)
                    foreach ($resultBookData as $key => $bookTodosTusLibros) {
                        $resultBookData[$key]['providers'] = $this->fetchProvidersDataByISBN($bookTodosTusLibros['isbn']); // Obtenemos y almacenamos la respuesta de las otras dos fuentes de datos
                    }
                } else {
                    $resultBookData['providers'] = isset($resultBookData['isbn']) ? $this->fetchProvidersDataByISBN($resultBookData['isbn']) : [];
                    $resultBookData = [$resultBookData]; // Devolvemos un array de 1 libro
                }
            }
        } catch (\Exception $exception) {
            print_r($exception);
        }

        return $this->createBooksFromArray($resultBookData);
    }

    private function fetchProvidersDataByISBN(string $isbn): array
    {
        return [$this->booksrunDataProvider->fetchData($isbn), $this->iberLibroDataProvider->fetchData($isbn)];
    }

    private function createBookFromArray(array $bookData, array $providers): Book
    {
        $bookProviders[] = new Provider($bookData['provider'], $this->bookFormater->formatPrecio($bookData['precio']), $bookData['buyUrl']); // Primer provider el principal, que viene en $bookData
        foreach ($providers as $provider) {
            $bookProviders[] = new Provider($provider['provider'], $this->bookFormater->formatPrecio($provider['precio']), $provider['buyUrl']);
        }

        return new Book(
            $bookData['titulo'],
            $bookData['autor'],
            $bookData['editorial'],
            $this->bookFormater->formatISBN($bookData['isbn']),
            $this->bookFormater->formatImagen($bookData['image']),
            $bookProviders,
        );
    }

    private function createBooksFromArray(array $booksData): array
    {
        $books = [];
        foreach ($booksData as $bookData) {
            $books[] = $this->createBookFromArray($bookData, $bookData['providers']);
        }

        return $books;
    }
}

