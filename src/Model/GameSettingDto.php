<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class GameSettingDto
{
    public function __construct(
        #[Assert\Count(min: 1)]
        public readonly array $chapterNumbers,
        #[Assert\Positive()]
        public readonly int $numberOfQuestionPerGame,
    ) {
    }
}