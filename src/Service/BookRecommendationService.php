<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RequestStack;

class BookRecommendationService {
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function storeBookSearch(array $books): void
    {
        $response = new Response();

        $previousSearches = $this->getPreviousSearches();

        foreach($books as $book) {
            $previousSearches[] = $book;
        }

        if(count($previousSearches) > 50) {
            $previousSearches = array_slice($previousSearches, -50);
        }

        $cookie = new Cookie(
            'book_searches',
            json_encode($previousSearches),
            time() + (2 * 365 * 24 * 60 * 60)
        );

        $response->headers->setCookie($cookie);
        $response->send();
    }

    public function getPreviousSearches(): array
    {
        $request = $this->requestStack->getCurrentRequest();
        if($request->cookies->has('book_searches')) {
            return json_decode($request->cookies->get('book_searches'), true);
        }

        return [];
    }

    public function getRandomBooks(): array
    {
        $previousSearches = $this->getPreviousSearches();
        if(count($previousSearches) <= 9) {
            return $previousSearches;
        }

        $randomKeys = array_rand($previousSearches, 9);
        return array_intersect_key($previousSearches, array_flip($randomKeys));
    }
}
