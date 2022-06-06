<?php

// src/Controller/ProgramController.php
namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Form\ProgramType;
use App\Repository\EpisodeRepository;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use App\Service\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/program', name: 'program_')]
class ProgramController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findAll();

        return $this->render('program/index.html.twig', ['programs' => $programs,]);
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request, ProgramRepository $programRepository, Slugify $slugify): Response
    {
        $program = new Program();

        // create the form
        $form = $this->createForm(ProgramType::class, $program);

        // Get data from HTTP request
        $form->handleRequest($request);

        // Was the form submitted ?
        if ($form->isSubmitted() && $form->isValid()) {
            // slug generation
            $slug = $slugify->generate($program->getTitle());
            $program->setSlug($slug);

            $programRepository->add($program, true);

            // Redirect to categories list
            return $this->redirectToRoute('program_index');
        }

        // render said form
        return $this->RenderForm('program/new.html.twig', ['form' => $form]);
    }

    #[Route('/{slug}', name: 'show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(
        SeasonRepository $seasonRepository,
        Program          $program,
    ): Response
    {
        $seasons = $seasonRepository->findBy(['program' => $program]);

        return $this->render('program/program_show.html.twig', ['program' => $program, 'seasons' => $seasons]);
    }

    #[Route('/{slug}/seasons/{slug_season}', name: 'season_show', methods: ['GET'])]
    public function showSeason(
        EpisodeRepository $episodeRepository,
        Program           $program,
        Season            $season,
    ): Response
    {
        $episodes = $episodeRepository->findBy(['season' => $season]);

        return $this->render(
            'program/season_show.html.twig',
            [
                'program' => $program,
                'season' => $season,
                'episodes' => $episodes,
            ]
        );
    }

    #[Route('/{slug}/seasons/{slug_season}/episode/{slug_episode}', name: 'episode_show', methods: ['GET'])]
    public function showEpisode(
        Program $program,
        Season  $season,
        Episode $episode,
    ): Response
    {
        return $this->render(
            'program/episode_show.html.twig',
            [
                'program' => $program,
                'season' => $season,
                'episode' => $episode,
            ]
        );
    }
}
