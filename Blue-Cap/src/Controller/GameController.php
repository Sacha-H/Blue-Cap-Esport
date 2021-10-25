<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Player;
use App\Form\GameType;
use App\Repository\GameRepository;
use App\Repository\PlayerRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


/**
 * @Route("/game")
 */
class GameController extends AbstractController
{
    /**
     * @Route("/", name="game_index", methods={"GET"})
     */
    public function index(GameRepository $gameRepository): Response
    {
        return $this->render('game/index.html.twig', [
            'games' => $gameRepository->findAll(),
        ]);
    }

    public function menu(GameRepository $gameRepository): Response
    {
        return $this->render('game/menu.html.twig', [
            'games' => $gameRepository->findAll(),
        ]);
    }


    /**
     * @Route("/new", name="game_new", methods={"GET","POST"})
     */
    public function new(Request $request, SluggerInterface $slugger): Response
    {
        $game = new Game();
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageGame = $form->get('imageGame')->getData();
            if ($imageGame) {
            $originalFilename = pathinfo($imageGame->getClientOriginalName(),
           PATHINFO_FILENAME);
          
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$imageGame->guessExtension();
            
            try {
            $imageGame->move(
            $this->getParameter('images_directory'),
            $newFilename
            );
            } catch (FileException $e) {
            }
            $game->setImageGame($newFilename);
            }
           

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($game);
            $entityManager->flush();

            return $this->redirectToRoute('game_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('game/new.html.twig', [
            'game' => $game,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="game_show", methods={"GET"})
     */
    public function show(Game $game ,GameRepository $gameRepository, PlayerRepository $playerRepository, Player $player ): Response
    {
        $id = $game->getId();

        
        return $this->render('game/show.html.twig', [
            'game' => $game,
            'games' => $gameRepository->findAll(),
            'players' => $playerRepository->findByGame($id),
            
        ]);
    }

    /**
     * @Route("/{id}/edit", name="game_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Game $game, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageGame = $form->get('imageGame')->getData();
            if ($imageGame) {
            $originalFilename = pathinfo($imageGame->getClientOriginalName(),
           PATHINFO_FILENAME);
          
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$imageGame->guessExtension();
            
            try {
            $imageGame->move(
            $this->getParameter('images_directory'),
            $newFilename
            );
            } catch (FileException $e) {
            }
            $game->setImageGame($newFilename);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('game_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('game/edit.html.twig', [
            'game' => $game,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="game_delete", methods={"POST"})
     */
    public function delete(Request $request, Game $game): Response
    {
        if ($this->isCsrfTokenValid('delete'.$game->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($game);
            $entityManager->flush();
        }

        return $this->redirectToRoute('game_index', [], Response::HTTP_SEE_OTHER);
    }
}
