<?php

namespace App\Controller;

use App\Entity\Movies;
use App\Repository\MoviesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{

    private MoviesRepository $moviesRepository;

    public function __construct(MoviesRepository $moviesRepository)
    {
        $this->moviesRepository = $moviesRepository;
    }

    #[Route('/movies', name: 'movie')]
    public function index(): Response
    {
        return $this->render('movie/index.html.twig', [
            'movies' => $this->moviesRepository->findAll(),
        ]);
    }

    #[Route('/movies/{id}', name: 'movie_show')]
    public function show(Movies $movies): Response
    {
        return $this->render('movie/show.html.twig', [
            'movie' => $movies,
        ]);
    }
}