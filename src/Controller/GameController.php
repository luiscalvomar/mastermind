<?php

namespace App\Controller;

use App\Constants\Constants;
use App\Entity\Game;
use App\Entity\Play;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GameController extends AbstractController
{

    /**
     * @param GameRepository $gameRepository
     * List all games being played.
     * @return Response Games in JSON format
     */

    /** List playing games
     * @param GameRepository $gameRepository
     * @return Response
     */
    #[Route('game/list', name: 'list_games', methods: ['GET'])]
    public function listPlaying(GameRepository $gameRepository): Response
    {
        $games = $gameRepository->findBy(['status' => 'playing']);

        // Exclude el campo 'moves' to avoid recursive error
        $gamesArray = array_map(function(Game $game) {
            return [
                'id' => $game->getId(),
                'name' => $game->getName(),
                'createdAt' => $game->getCreatedAt(),
                'colorCode' => $game->getColorCode(),
                'status' => $game->getStatus(),
            ];
        }, $games);

        if (empty($gamesArray)) {
            return $this->json(['error' => 'No games found']);
        }

        return $this->json($gamesArray);
    }

    /**
     * Shows a game data. Game is passed as parameter
     * @param Game $game Game to check data.
     * @return Response
     */
    #[Route('game/{id}', name: 'game_details', methods: 'GET')]
    public function gameDetails(int $id, GameRepository $gameRepository): Response
    {
        $game = $gameRepository->find($id);
        if (!$game) {
            return $this->json(['error' => 'Game not found']);
        }

        // Construct JSON response
        $gameData = [
            'id' => $game->getId(),
            'name' => $game->getName(),
            'createdAt' => $game->getCreatedAt()->format('Y-m-d H:i:s'),
            'colorCode' => $game->getColorCode(),
            'status' => $game->getStatus(),
            'moves' => []
        ];

        foreach ($game->getMoves() as $move) {
            $gameData['moves'][] = [
                'id' => $move->getId(),
                'receivedAt' => $move->getReceivedAt()->format('Y-m-d H:i:s'),
                'proposedCode' => $move->getProposedCode(),
                'evaluationResult' => $move->getEvaluationResult()
            ];
        }

        return $this->json($gameData);
    }

    /**
     *  Creates a new game and returns it
     * @param Request $request HTTP request (name))
     * @param EntityManagerInterface $entityManager
     * @return Response Game
     */
    #[Route('/game/create', name: 'app_game_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Gets name from request
        $name = $request->get('name');
        // Generate color code
        $colorCode = $this->colorCodeGenerate();
        // Create a new game with its parameters.
        $game = new Game();
        $game->setName($name);
        $game->setCreatedAt(new \DateTimeImmutable());
        $game->setColorCode($colorCode);
        $game->setStatus('playing');
        // Notice doctrine this is a new entity and apply it (flush)
        $entityManager->persist($game);
        $entityManager->flush();

        return $this->json($game);
    }

    /** Shuffle mastermind allowed colours to make a random color code
     * @return string Color code
     */
    private function colorCodeGenerate(): string
    {
        $colours = Constants::ALLOWED_COLOURS;
        shuffle($colours);
        return implode(',', array_slice($colours, 0, 4));
    }
}
