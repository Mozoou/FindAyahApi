<?php

namespace App\Model;

use App\Service\QuranDataFetcher\Model\Verse;

class GameQuestionDto
{
    public Verse $verseToFind;
    public Verse $goodVerseAnswer;
    public array $badVersesAnswer;

    public function getVerseToFind(): Verse
    {
        return $this->verseToFind;
    }

    public function setVerseToFind(Verse $verseToFind): self
    {
        $this->verseToFind = $verseToFind;

        return $this;
    }

    public function getGoodVerseAnswer(): Verse
    {
        return $this->goodVerseAnswer;
    }

    public function setGoodVerseAnswer($goodVerseAnswer): self
    {
        $this->goodVerseAnswer = $goodVerseAnswer;

        return $this;
    }

    public function getBadVersesAnswer(): array
    {
        return $this->badVersesAnswer;
    }

    public function setBadVersesAnswer(array $badVersesAnswer): self
    {
        $this->badVersesAnswer = $badVersesAnswer;

        return $this;
    }
}