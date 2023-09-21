<?php

namespace App\Command;

use App\Entity\Characters;
use App\Entity\Movies;
use App\Repository\MoviesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'starwars:import',
    description: 'This command imports data from the Star Wars universe.',
)]
class StarwarsImportCommand extends Command
{
    private HttpClientInterface $client;
    private EntityManagerInterface $entityManager;
    private MoviesRepository $moviesRepository;

    public function __construct(HttpClientInterface $client, EntityManagerInterface $entityManager, MoviesRepository $moviesRepository) {
        $this->client = $client;
        $this->entityManager = $entityManager;
        $this->moviesRepository = $moviesRepository;
        parent::__construct();
    }
    protected function configure(): void
    {
        $this
            ->setDescription('Import Star Wars data.')
            ->setHelp('This command imports data from the Star Wars universe.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $response = $this->client->request(
            'GET',
            'https://swapi.dev/api/films',
        );
        $content = $response->toArray();

        foreach ($content['results'] as $film) {
            $filmId = intval(preg_replace('/[^0-9]/', '', $film['url']));

            $existingFilm = $this->moviesRepository->findOneBy(['id' => $filmId]);

            if (!$existingFilm) {
                $movie = new Movies();
                $movie->setId($filmId); // Utilisez l'ID du film comme identifiant
                $movie->setName($film['title']);
                $this->entityManager->persist($movie);
            }
        }

        $this->entityManager->flush();

        $totalPages = 3;

        for ($page = 1; $page <= $totalPages; $page++) {
            $response = $this->client->request(
                'GET',
                'https://swapi.dev/api/people/?page=' . $page,
            );

            $content = $response->toArray();

            foreach ($content['results'] as $person) {
                $character = new Characters();
                $character->setName($person['name']);
                $character->setHeight($person['height']);
                $character->setMass($person['mass']);
                $character->setGender($person['gender']);
                $character->setPicture(1);

                foreach ($person['films'] as $filmUrl) {
                    $filmId = intval(preg_replace('/[^0-9]/', '', $filmUrl));

                    $film = $this->moviesRepository->findOneBy(['id' => $filmId]);

                    if ($film) {
                        $character->addMovie($film);
                    }
                }

                $this->entityManager->persist($character);
            }
        }

        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
