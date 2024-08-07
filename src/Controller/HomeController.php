<?php

namespace App\Controller;

use App\Form\SettingsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, GameController $gameController): Response
    {
        $form = $this->createForm(SettingsType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $chaptersNumbers = $this->getChaptersNumbers($form->get('chapters')->getData());

            $questions = $gameController->prepareQuestions($chaptersNumbers, $form->get('questions')->getData());

            if ($questions) {
                $request->getSession()->set('questions', $questions);
                return $this->redirectToRoute('app_game');
            }
        }

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/play', name: 'app_game')]
    public function game(Request $request): Response
    {
        if ($request->getSession()->get('questions') === null) {
            $this->denyAccessUnlessGranted('');
        }

        return $this->render('home/game.html.twig', [
            'questions' => $request->getSession()->get('questions'),
        ]);
    }

    #[Route('/results', name: 'app_game_result')]
    public function gameResult(Request $request): Response
    {
        return $this->render('home/results.html.twig', [
            'questions' => $request->getSession()->get('questions'),
            'answers' => $request->request->all(),
        ]);
    }

    private function getChaptersNumbers(array $chapters): array
    {
        $chaptersNumbers = [];

        /** @var Chapter $chapter */
        foreach ($chapters as $chapter) {
            $chaptersNumbers[] = $chapter->getNumber();
        }

        return $chaptersNumbers;
    }
}
