<?php

namespace App\Controller\Api;

use App\Controller\GameController;
use App\Model\GameSettingDto;
use App\Service\RandomVerse\RandomVerse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class GameApiController extends AbstractController
{
    public function __construct(
        private GameController $gameController
    ) {
    }

    #[Route('/fetch_game_questions', format: 'json')]
    public function retriveGameQuestions(
        #[MapRequestPayload()] GameSettingDto $gameSettings
    ): JsonResponse {
        return $this->json($this->gameController->prepareQuestions($gameSettings->chapterNumbers, $gameSettings->numberOfQuestionsPerGame));
    }
}
