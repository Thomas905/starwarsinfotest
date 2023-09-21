<?php

namespace App\Controller;

use App\Entity\Characters;
use App\Form\CharacterFormType;
use App\Form\SearchFormType;
use App\Repository\CharactersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PeopleController extends AbstractController
{

    private CharactersRepository $charactersRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(CharactersRepository $charactersRepository, EntityManagerInterface $entityManager)
    {
        $this->charactersRepository = $charactersRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/peoples', name: 'peoples')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(SearchFormType::class);
        $form->handleRequest($request);

        $characters = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $search = $form->getData()['search'];
            if ($search !== null) {
                $characters = $this->charactersRepository->findLikeName($search);
            }
        }

        if (empty($characters)) {
            $characters = $this->charactersRepository->findAll();
        }

        return $this->render('people/index.html.twig', [
            'characters' => $characters,
            'form' => $form->createView(),
        ]);

    }

    #[Route('/peoples/{id}', name: 'people')]
    public function show(Characters $characters, Request $request): Response
    {
        $form = $this->createForm(CharacterFormType::class, $characters);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pictureFile = $form->get('picture')->getData();
            if ($pictureFile) {
                $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$pictureFile->guessExtension();
                $pictureFile->move(
                        $this->getParameter('pictures_directory'),
                        $newFilename
                    );
                $characters->setPicture($newFilename);
            }

            $this->entityManager->flush();
            return $this->redirectToRoute('peoples');
        }

        return $this->render('people/edit.html.twig', [
            'character' => $characters,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/peoples/{id}/delete', name: 'people_delete')]
    public function delete(Characters $characters): Response
    {
        $this->entityManager->remove($characters);
        $this->entityManager->flush();
        return $this->redirectToRoute('peoples');
    }
}