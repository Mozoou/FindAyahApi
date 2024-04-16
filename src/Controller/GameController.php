<?php

namespace App\Controller;

use App\Model\GameQuestionDto;
use App\Service\RandomVerse\RandomVerse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GameController extends AbstractController
{
    public function __construct(
        private RandomVerse $randomVerse,
    )
    {}

    public function prepareQuestions(array $chapterNumbers, int $numberOfQuestionsPerGame): array
    {
        $questions = [];
        $previousQuestion = [];

        for ($i = 0; $i < $numberOfQuestionsPerGame; $i++) {
            /** @var GameQuestionDto[] $questions */
            $questions[] = $this->randomVerse->randomVerseFromChapterNumbers($chapterNumbers, $previousQuestion);
            $previousQuestion[] = $questions[$i]->getVerseToFind()->getNumber();
        }

        return $questions;
    }
}
