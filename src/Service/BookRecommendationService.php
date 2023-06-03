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
        $session = $this->requestStack->getCurrentRequest()->getSession();

        $previousSearches = $session->get('book_searches', []);

        $session->set('book_searches', array_merge($previousSearches, $books));
    }

    public function getPreviousSearches(): array
    {
        $session = $this->requestStack->getCurrentRequest()->getSession();

        $previousSearches = $session->get('book_searches', []);

        $ramdomKeys = (count($previousSearches) > 9) ? array_rand($previousSearches, 9) : array_keys($previousSearches);

        return array_intersect_key($previousSearches, array_flip($ramdomKeys));
    }
}
