<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class RandomAyahService
{
    private const AYAH_URL = 'http://api.alquran.cloud/v1/surah';
    private const IMAGE_URL = 'https://everyayah.com/data/images_png';

    private array $surahs = [];

    public function __construct(
        private HttpClientInterface $client,
    ) {
    }

    public function getSurahs(): array
    {
        if ($this->surahs) {
            return $this->surahs;
        }

        $surahs = [];
        $response = $this->client->request(
            'GET',
            self::AYAH_URL
        );

        foreach ($response->toArray()['data'] as $surah) {
            $surahs[$surah['name']] = $surah['number'];
            $this->surahs[$surah['number']] = $surah;
        }

        return $surahs;
    }

    public function randomAyahFromsurahs(array $surah): array
    {
        $surahNumber = array_rand(array_flip($surah), 1);
        $ayahs = $this->getSurahAyahs($surahNumber);
        return $this->getRandomAya($ayahs, $surahNumber);
    }

    private function validateSurah(int $surah): bool
    {
        $this->getSurahs();
        return array_key_exists($surah, $this->surahs);
    }

    private function getSurahAyahs(int $surah): array
    {
        if (!$this->validateSurah($surah)) {
            return []; // TODO: Ajouter une erreur
        }

        $response = $this->client->request(
            'GET',
            self::AYAH_URL .'/'. $surah
        );

        return $response->toArray()['data']['ayahs'];
    }

    private function getRandomAya(array $ayahs, int $surahNumber): array
    {
        $ayah = array_rand($ayahs);
        if ($ayah !== 0) {
            $ayah--;
        }

        $baseAyah = $ayahs[$ayah];
        $goodAyah = $ayahs[$ayah+1];

        return [
            'ayah' => [
                'data' => $this->getAyahDataWithImage($baseAyah, $surahNumber, $baseAyah['numberInSurah']),
            ],
            'goodAyah' => [
                'data' => $this->getAyahDataWithImage($goodAyah, $surahNumber, $goodAyah['numberInSurah']),
            ],
            'wrongAyahs' => [
                // Ajouter le nombre de réponse en fnct du niveau
                [
                    'data' => $this->getAyahDataWithImage($baseAyah, $surahNumber, $baseAyah['numberInSurah']),
                ],
                [
                    'data' => $this->getAyahDataWithImage($baseAyah, $surahNumber, $baseAyah['numberInSurah']),
                ],
                [
                    'data' => $this->getAyahDataWithImage($baseAyah, $surahNumber, $baseAyah['numberInSurah']),
                ]
            ]
        ];
    }

    protected function getAyahDataWithImage(array $ayahData, int $surahNumber, int $numberInSurah): array
    {
        $ayahData['img'] = self::IMAGE_URL .'/'. $surahNumber . '_' . $numberInSurah . '.png';

        return $ayahData;
    }
}
