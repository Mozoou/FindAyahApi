<?php

namespace App\Service\QuranDataFetcher;

use App\Service\QuranDataFetcher\Model\Verse;
use App\Service\QuranDataFetcher\Validator\ChapterValidator;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class VerseFetcher
{
    private const SURAH_URL = 'http://api.alquran.cloud/v1/surah';

    public function __construct(
        private HttpClientInterface $client,
        private ChapterValidator $chapterValidator,
        private ?Serializer $serializer = null,
    ) {
        $this->serializer = new Serializer([new ObjectNormalizer()], []);
    }

    /**
     * Retrives all the verses from a valid chapter number
     * 
     * @param int $chapterNumber
     * 
     * @throws \InvalidArgumentException if the chapter number is not a valid chapter number
     *                                   (check Validator/ChapterValidator.php)
     * 
     * @return Verse[]
     */
    public function getAllVersesFromChapterNumber(int $chapterNumber): array
    {
        if ($this->chapterValidator->validate($chapterNumber)) {
            throw new \InvalidArgumentException('The number of surah doesn\'t exist !');
        }

        $response = $this->client->request(
            'GET',
            self::SURAH_URL . '/' . $chapterNumber
        );

        $verses = [];
        foreach ($response->toArray()['data']['ayahs'] as $ayah) {
            $verses[] = $this->serializer->denormalize($ayah, Verse::class);
        }

        return $verses;
    }
}
