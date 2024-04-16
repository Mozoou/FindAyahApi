<?php

namespace App\Service\RandomVerse;

use App\Model\GameQuestionDto;
use App\Service\QuranDataFetcher\Model\Verse;
use App\Service\QuranDataFetcher\VerseFetcher;

class RandomVerse
{
    public function __construct(
        private VerseFetcher $verseFetcher,
    ) {
    }

    public function randomVerseFromChapterNumbers(array $chapterNumbers, array $previousVersesQuestions): GameQuestionDto
    {
        $ayahs = [];
        foreach ($chapterNumbers as $chapterNumber) {
            array_push($ayahs, ...$this->verseFetcher->getAllVersesFromChapterNumber($chapterNumber));
        }

        return $this->prepareVersesQuestion($ayahs, $previousVersesQuestions);
    }

    private function prepareVersesQuestion(array $verses, array $previousVersesQuestions = []): GameQuestionDto
    {
        $gameQuestion = new GameQuestionDto();

        $randomVersesIndexes = $this->getRandomVersesIndexes($verses);
        $verseToFind = $this->getRandomVersesWithoutDuplicate($verses, $previousVersesQuestions, $randomVersesIndexes);
        $goodVerseAnswer = $this->getGoodVerseAnswer($verses, $verseToFind);
        $badVersesAnswer = $this->getBadVersesAnswer($verses, $randomVersesIndexes, $verseToFind);

        return $gameQuestion
            ->setVerseToFind($verseToFind)
            ->setGoodVerseAnswer($goodVerseAnswer)
            ->setBadVersesAnswer($badVersesAnswer);
    }

    private function getRandomVersesIndexes(array $verses): array
    {
        $versesIndexes = [];

        for ($i = 0; $i <= count($verses); $i++) {
            if (count($versesIndexes) >= 3) {
                break;
            }

            $randIndex = rand(0, count($verses) - 1);
            while (in_array($randIndex, $versesIndexes)) {
                $randIndex = rand(0, count($verses) - 1);
            }
            $versesIndexes[] = $randIndex;
        }

        foreach ($versesIndexes as $key => $verseIndex) {
            if ($verseIndex !== 0) {
                $versesIndexes[$key] = $verseIndex - 1;
            }
        }

        return $versesIndexes;
    }

    private function getRandomVersesWithoutDuplicate(array $verses, array $previousVersesQuestions, array $randomVersesIndexes): Verse
    {
        $indexToFind = array_rand($randomVersesIndexes);
        $verseToFind = $verses[$randomVersesIndexes[$indexToFind]];

        foreach ($previousVersesQuestions as $question) {
            while ($verseToFind->getNumber() === $question) {
                $indexToFind = rand(0, count($randomVersesIndexes) - 1);
                $verseToFind = $verses[$indexToFind];

                $matched = false;
                foreach ($previousVersesQuestions as $prevQuestion) {
                    if ($verseToFind->getNumber() === $prevQuestion) {
                        $indexToFind = rand(0, count($randomVersesIndexes) - 1);
                        $verseToFind = $verses[$indexToFind];
                    }
                }

                if (!$matched) {
                    break;
                }
            }
        }

        return $verseToFind;
    }

    private function getGoodVerseAnswer(array $ayahs, Verse $ayah): Verse
    {
        return $ayahs[array_search($ayah, $ayahs) + 1];
    }

    private function getBadVersesAnswer(array $verses, array $randomVersesIndexes, Verse $verseToFind): array
    {
        $badVersesAnswer = [];
        foreach ($randomVersesIndexes as $index) {
            if ($verses[$index]->getNumber() !== $verseToFind->getNumber()) {
                $badVersesAnswer[] = $verses[$index];
            }
        }

        return $badVersesAnswer;
    }
}
