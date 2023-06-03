<?php

namespace App\Controller;

use App\Factory\BookFactory;
use App\Service\BookRecommendationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class HomeController extends AbstractController
{
    private BookFactory $bookFactory;
    private BookRecommendationService $bookRecommendationService;

    /**
     * @param BookFactory $bookFactory
     * @param BookRecommendationService $bookRecommendationService
     */
    public function __construct(BookFactory $bookFactory, BookRecommendationService $bookRecommendationService)
    {
        $this->bookFactory = $bookFactory;
        $this->bookRecommendationService = $bookRecommendationService;
    }

    /**
     * @Route("/", name="app_home")
     */
    public function index(): Response
    {
        $popularBooks = $this->bookFactory->getPopularBooks();
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers);
        $popularBooksArray = $serializer->normalize($popularBooks, null);
        $recommendedBooks = $this->bookRecommendationService->getPreviousSearches();

        return $this->render('home.html.twig', ['recommendedBooks' => $recommendedBooks, 'popularBooks' => $popularBooksArray]);
    }



    private function shuffleArray($array): array {
        shuffle($array);
        return $array;
    }

    private function getRandomPrice(): string {
        $num = mt_rand(1000, 3000) / 100;

        return number_format($num, 2) . "€";
    }
}
