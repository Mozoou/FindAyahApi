<?php

namespace App\Controller;

use App\Model\GameSettingDto;
use App\Service\RandomAyahService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class AppController extends AbstractController
{
    public function __construct(
        private RandomAyahService $randomAyahService,
    ){
    }

    #[Route('/api/test', name: 'app_test')]
    public function test(): JsonResponse
    {
        return $this->json(['test' => 4]);
    }

    #[Route('/api/gamedata', name: 'app_game', format: 'json')]
    public function game(
        #[MapRequestPayload()] GameSettingDto $gameSettings
        
    ): JsonResponse {
        $data = [];

        $questionNumber = $gameSettings->numberOfQuestionPerGame;
        $surahs = $gameSettings->surahs;

        for ($i = 0; $i < $questionNumber; $i++) {
            // TODO: Remove duplicate
            $data[] = $this->getQuestionInSurveyJsonResponse($surahs);
        }

        return $this->json(['data' => $data]);
    }

    private function getQuestionInSurveyJsonResponse(array $surah): array
    {
        return $this->randomAyahService->randomAyahFromsurahs($surah);

        // dd($element);

        // SurveyJs json response
        // elements: [{
        //     type: "radiogroup",
        //     name: "civilwar",
        //     title: "When was the American Civil War?",
        //     choices: [
        //         "1796-1803", "1810-1814", "1861-1865", "1939-1945"
        //     ],
        //     correctAnswer: "1861-1865"
        // }]
    }
}
