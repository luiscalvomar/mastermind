<?php

namespace App\Controller;

use App\Constants\Constants;
use App\Entity\Play;
use App\Repository\GameRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PlayController extends AbstractController
{
    /** Plays a mastermind round and return black and white keys (black|white)
     * @param Request $request
     * @param GameRepository $gameRepository
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/play', name: 'play', methods: ['POST'])]
    public function play(Request $request, GameRepository $gameRepository, EntityManagerInterface $entityManager): Response
    {
        $gameId = $request->get('gameId');
        $proposedCode = $request->get('proposedCode');
        $game = $gameRepository->find($gameId);


        // Check if game exist. If not return error.
        if(!$game) {
            return $this->json(['error' => 'Game not found']);
        }

        // Check game state, ig game does not exist or is finalized, return.

        if ($game->getStatus() !== 'playing') {
            return $this->json(['error' => 'Game ended whith status: '.$game->getStatus()]);
        }

        // Evaluate score
        $score = $this->gameEval($game->getColorCode(), $proposedCode);

        if($score === 'invalid_color') {
            return $this->json([
                'error' => 'Wrong color code. Allowed colors are: '.implode(',',Coblunstants::ALLOWED_COLOURS),
            ]);
        }

        $play = new Play();
        $play->setGame($game);
        $play->setReceivedAt(new DateTimeImmutable());
        $play->setProposedCode($proposedCode);
        $play->setEvaluationResult($score);
        $entityManager->persist($play);


        $moves = $game->getMoves();
        if (count($moves) >= 10) {
            $game->setStatus('failed');
        } else {
            if ($score === '4|0') {
                $game->setStatus('victory');
            }
        }

        $moves_left = 10 - count($moves);

        $entityManager->flush();
        return $this->json([
            'score' => $score,
            'state' => $game->getStatus(),
            'left' => $moves_left
        ]);
    }

    /** Evaluate score. Black pins are color on its position, white colors in code.
     * @param string $code
     * @param string $proposedCode
     * @return string black|white
     */
    private function gameEval(string $code, string $proposedCode): string {

        $codeArray = explode(',', $code);
        $proposedArray = explode(',', $proposedCode);
        // Check valid colors
        if (empty(array_diff($proposedArray, Constants::ALLOWED_COLOURS)) === false || count($proposedArray) !== 4) {
            return 'invalid_color';
        }

        $blackKeys = 0;
        $whiteKeys = 0;

        $codeEval = array_fill(0, count($codeArray), false);
        $proposedEval = array_fill(0, count($proposedArray), false);


        // Count black keys
        for ($i = 0; $i < count($codeArray); $i++) {
            if($codeArray[$i] === $proposedArray[$i]) {
                $blackKeys++;
                $codeEval[$i] = true;
                $proposedEval[$i] = true;
            }
        }

        // Second iteration to count white keys. Avoid looping again on evaluated positions.
        for($i = 0; $i < count($codeArray); $i++) {
            if($codeEval[$i] !== true) {
                for ($j = 0; $j < count($proposedArray); $j++) {
                    if($proposedEval[$j] !== true && ($codeArray[$i] === $proposedArray[$j])) {
                        $whiteKeys++;
                        $codeEval[$i] = true;
                        $proposedEval[$i] = true;
                        // Stop checking with a match.
                        break;
                    }
                }
            }
        }
        return $blackKeys.'|'.$whiteKeys;
    }

}
