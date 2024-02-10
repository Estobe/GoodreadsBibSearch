<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\GoodReadsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public function __construct(
        private readonly GoodReadsService $goodReadsService,
    ) {
    }

    #[Route('/', name: 'app.home.show')]
    public function showBooks(): Response
    {
        $books     = $this->goodReadsService->getIncompleteTaggedBooksFromShelf($_ENV['GOODREADS_USERNAME'], 'to-read');

        return $this->render('home.html.twig', [
            'books'     => $books,
        ]);
    }
}
