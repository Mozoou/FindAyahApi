<?php

namespace App\Controller\Api;

use App\Model\GameSettingDto;
use App\Service\RandomVerse\RandomVerse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class GameApiController extends AbstractController
{
    public function __construct(
        private RandomVerse $randomVerse,
    ) {
    }

    #[Route('/fetch_game_questions', format: 'json')]
    public function retriveGameQuestions(
        #[MapRequestPayload()] GameSettingDto $gameSettings
    ): JsonResponse {
        $chapterNumbers = $gameSettings->chapterNumbers;
        $questionNumber = $gameSettings->numberOfQuestionPerGame;

        return $this->json($this->randomVerse->randomAyahFromsurahs($chapterNumbers, $questionNumber));
    }
}
