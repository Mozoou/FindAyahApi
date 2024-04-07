<?php

namespace App\Service\RandomVerse;

use App\Service\QuranDataFetcher\Model\Verse;
use App\Service\QuranDataFetcher\VerseFetcher;

class RandomVerse
{
    public function __construct(
        private VerseFetcher $verseFetcher,
    ) {
    }

    public function randomAyahFromsurahs(array $surahs, int $questionNumber): array
    {
        $ayahs = [];
        foreach ($surahs as $surah) {
            array_push($ayahs, ...$this->verseFetcher->getAllVersesFromChapterNumber($surah));
        }

        $data = [];
        for ($i = 1; $i <= $questionNumber; $i++) {
            $data[] = $this->prepareAyahsQuestion($ayahs, $data);
        }

        return $data;
    }

    private function prepareAyahsQuestion(array $ayahs, array $previousAyahQuestion = []): array
    {
        $randomAyahsIndexes = $this->getRandomAyahsIndexesFromChoices($ayahs);
        $ayahToFind = $this->getRandomAyahWithoutDuplicate($ayahs, $previousAyahQuestion, $randomAyahsIndexes);

        $goodAnswerAyah = $this->getGoodAnswerAyah($ayahs, $ayahToFind);
        $wrongAyahs = [];
        foreach ($randomAyahsIndexes as $index) {
            if ($ayahs[$index]->getNumber() !== $ayahToFind->getNumber()) {
                $wrongAyahs[] = $ayahs[$index];
            }
        }

        return [
            'ayah' => $ayahToFind,
            'goodAyah' => $goodAnswerAyah,
            'wrongAyahs' => [
                ...$wrongAyahs
            ],
        ];
    }

    private function getRandomAyahsIndexesFromChoices(array $ayahs): array
    {
        $ayahsIndexes = [];

        for ($i = 0; $i <= count($ayahs); $i++) {
            if (count($ayahsIndexes) >= 3) {
                break;
            }

            $randIndex = rand(0, count($ayahs) - 1);
            while (in_array($randIndex, $ayahsIndexes)) {
                $randIndex = rand(0, count($ayahs) - 1);
            }
            $ayahsIndexes[] = $randIndex;
        }

        foreach ($ayahsIndexes as $key => $ayahIndex) {
            if ($ayahIndex !== 0) {
                $ayahsIndexes[$key] = $ayahIndex - 1;
            }

            if ($ayahIndex === 0) {
                $ayahsIndexes[$key] = $ayahIndex + 1;
            }
        }

        return $ayahsIndexes;
    }

    private function getRandomAyahWithoutDuplicate(array $ayahs, array $previousAyahQuestion, array $randomAyahsIndexes): Verse
    {
        $indexToFind = array_rand($randomAyahsIndexes);
        $ayahToFind = $ayahs[$randomAyahsIndexes[$indexToFind]];

        foreach ($previousAyahQuestion as $question) {
            while ($ayahToFind->getNumber() === $question['ayah']->getNumber()) {
                $indexToFind = rand(0, count($randomAyahsIndexes) - 1);
                $ayahToFind = $ayahs[$indexToFind];

                // Check if the newly generated ayah matches any previous ayah
                $matched = false;
                foreach ($previousAyahQuestion as $prevQuestion) {
                    if ($ayahToFind->getNumber() === $prevQuestion['ayah']->getNumber()) {
                        $indexToFind = rand(0, count($randomAyahsIndexes) - 1);
                        $ayahToFind = $ayahs[$indexToFind];
                    }
                }

                if (!$matched) {
                    // Break out of the while loop if a unique ayah is found
                    break;
                }
            }
        }

        return $ayahToFind;
    }

    private function getGoodAnswerAyah(array $ayahs, Verse $ayah): Verse
    {
        return $ayahs[array_search($ayah, $ayahs) + 1];
    }
}
