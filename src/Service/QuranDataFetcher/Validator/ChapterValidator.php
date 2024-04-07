<?php

namespace App\Service\QuranDataFetcher\Validator;

class ChapterValidator
{
    public function validate(int $surah): bool
    {
        return $surah < 0 || $surah > 114;
    }
}
