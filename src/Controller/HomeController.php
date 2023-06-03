<?php

namespace App\Controller;

use App\Factory\BookFactory;
use App\Service\BookRecommendationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class HomeController extends AbstractController
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
     * @Route("/", name="app_home")
     * @throws ExceptionInterface
     */
    public function index(): Response
    {
        $popularBooks = $this->bookFactory->getPopularBooks();
        $popularBooksArray = $this->serializer->normalize($popularBooks, null);
        $recommendedBooks = $this->bookRecommendationService->getPreviousSearches();

        return $this->render('home.html.twig', ['recommendedBooks' => $recommendedBooks, 'popularBooks' => $popularBooksArray]);
    }

}
