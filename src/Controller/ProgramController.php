<?php

// src/Controller/ProgramController.php
namespace App\Controller;

use App\Repository\EpisodeRepository;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(
        int               $id,
        SeasonRepository  $seasonRepository,
        ProgramRepository $programRepository
    ): Response
    {
        $program = $programRepository->findOneBy(['id' => $id]);

        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : ' . $id . ' found in programs table.'
            );
        } else {
            $seasons = $seasonRepository->findBy(['program' => $program]);
        }

        return $this->render('program/show.html.twig', ['program' => $program, 'seasons' => $seasons]);
    }

    #[Route('/{programId}/seasons/{seasonId}', name: 'program_season_show', methods: ['GET'])]
    public function showSeason(
        int               $programId,
        int               $seasonId,
        ProgramRepository $programRepository,
        SeasonRepository  $seasonRepository,
    ): Response
    {
        $program = $programRepository->findOneBy(['id' => $programId]);
        $season = $seasonRepository->findOneBy(['id' => $seasonId]);

        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : ' . $programId . ' found in programs table.'
            );
        }

        if (!$season) {
            throw $this->createNotFoundException(
                'No season with id : ' . $seasonId . ' found in seasons table.'
            );
        }

        if ($season->getProgram()->getId() != $programId) {
            throw $this->createNotFoundException(
                'No season with id : ' . $seasonId . ' found in program (' . $programId . ') table.'
            );
        }

        return $this->render(
            'program/season_show.html.twig',
            [
                'program' => $program,
                'season' => $season,
            ]
        );
    }
}
