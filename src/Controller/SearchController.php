<?php

namespace App\Controller;

use App\Factory\BookFactory;
use App\Service\BookRecommendationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class SearchController extends AbstractController
{
    private BookFactory $bookFactory;
    private Serializer $serializer;
    private BookRecommendationService $bookRecommendationService;

    /**
     * @param BookFactory $bookFactory
     * @param BookRecommendationService $bookRecommendationService
     */
    public function __construct(BookFactory $bookFactory, BookRecommendationService $bookRecommendationService)
    {
        $this->bookFactory = $bookFactory;
        $this->bookRecommendationService = $bookRecommendationService;
        $normalizers = [new ObjectNormalizer()];
        $this->serializer = new Serializer($normalizers);
    }

    /**
     * @Route("/search", name="search")
     * @throws ExceptionInterface
     */
    public function search(Request $request): Response
    {
        $query = $request->query->get('q');
        $results = $this->bookFactory->queryBooks($query);
        $resultadosArray = $this->serializer->normalize($results, null);
        $this->bookRecommendationService->storeBookSearch($resultadosArray);

        return $this->render('search.html.twig', [
            'query' => $query,
            'resultados' => $resultadosArray,
        ]);
    }
}

