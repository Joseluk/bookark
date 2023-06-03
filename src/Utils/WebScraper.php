<?php

namespace App\Utils;

use Symfony\Component\DomCrawler\Crawler;

class WebScraper {
    const PROVIDER_TODOS_TUS_LIBROS = 'TodosTusLibros';
    const PROVIDER_IBER_LIBRO = 'IberLibro';

    private Crawler $crawler;

    public function __construct($html) {
        $this->crawler = new Crawler($html);
    }

    public function extractMultipleBooksDataTodosTusLibros(): ?array
    {
        $books = [];

        $this->crawler->filter('li.book:not(.booklist-banner)')->each(function (Crawler $node, $i) use (&$books) {
            $title = $node->attr('data-gtm-titulo');
            $author = $node->filter('h3.author a')->text();
            $image = $node->filter('img')->attr('src');
            $isbn = $node->attr('data-gtm-isbn');
            $price = $node->attr('data-gtm-precio');
            $editorial = $node->attr('data-gtm-editorial');
            $buyUrl = $node->filter('h2.title a')->attr('href');

            $books[] = [
                'titulo' => $title,
                'autor' => $author,
                'image' => $image,
                'isbn' => $isbn,
                'precio' => $price,
                'editorial' => $editorial,
                'buyUrl' => $buyUrl,
                'provider' => self::PROVIDER_TODOS_TUS_LIBROS
            ];
        });

        return $books;
    }

    public function extractSingleBookDataTodosTusLibros(): array
    {
        $bookImage = $this->crawler->filter('.book-top-left .book-image img.portada')->attr('src');
        $title = $this->crawler->filter('.book-top-center .title')->text();
        $author = $this->crawler->filter('.book-top-center .author a.author')->text();
        $editorial = $this->crawler->filter('.book-top-center .infobase a')->text();
        $isbn = $this->crawler->filter('.book-top-center .infobase span')->first()->text();
        $price = $this->crawler->filter('.cosasticky .total-book-price')->text();
        $buyUrl = $this->crawler->filter('head > link[rel="canonical"]')->attr('href');


        return [
            'image' => $bookImage,
            'titulo' => $title,
            'autor' => $author,
            'editorial' => $editorial,
            'isbn' => $isbn,
            'precio' => $price,
            'buyUrl' => $buyUrl,
            'provider' => self::PROVIDER_TODOS_TUS_LIBROS
        ];
    }

    public function extractSingleBookDataIberLibro(): array
    {
        try {
            $bookImage = $this->crawler->filter('.result-image .srp-item-image')->attr('src');
            $title = $this->crawler->filter('.result-data .title span')->text();
            $author = $this->crawler->filter('.result-data .author strong')->text();
            $editorial = $this->crawler->filter('.result-data .pub-data .opt-publisher')->text();
            $rawIsbn = $this->crawler->filter('.result-data .isbn span')->last()->text();
            $rawPrice = $this->crawler->filter('.result-data .item-price')->text();
            $rawBuyUrl = $this->crawler->filter('.result-data .result-detail h2.title a')->attr('href');

            $isbn = str_replace("ISBN 13: ", "", $rawIsbn);
            $price = str_replace("EUR ", "", $rawPrice);
            $buyUrl = "https://www.iberlibro.com" . $rawBuyUrl;
        } catch (\Exception $e) {
            $bookImage = null;
            $title = null;
            $author = null;
            $editorial = null;
            $isbn = null;
            $price = null;
            $buyUrl = null;
        }

        return [
            'image' => $bookImage,
            'titulo' => $title,
            'autor' => $author,
            'editorial' => $editorial,
            'isbn' => $isbn,
            'precio' => $price,
            'buyUrl' => $buyUrl,
            'provider' => self::PROVIDER_IBER_LIBRO
        ];
    }

    public function elementWithIdExists(string $id): bool {
        return $this->crawler->filter("#{$id}")->count() > 0;
    }
}

