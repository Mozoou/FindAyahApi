<?php

namespace App\Service\QuranDataFetcher\Model;

class Verse
{
    private int $number;

    private string $text;

    private int $numberInSurah;

    private int $juz;

    private int $manzil;

    private int $page;

    private int $ruku;

    private int $hizbQuarter;

    private bool $sajda;

    public function getNumber()
    {
        return $this->number;
    }

    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    public function getNumberInSurah()
    {
        return $this->numberInSurah;
    }

    public function setNumberInSurah($numberInSurah)
    {
        $this->numberInSurah = $numberInSurah;

        return $this;
    }

    public function getJuz()
    {
        return $this->juz;
    }

    public function setJuz($juz)
    {
        $this->juz = $juz;

        return $this;
    }

    public function getManzil()
    {
        return $this->manzil;
    }

    public function setManzil($manzil)
    {
        $this->manzil = $manzil;

        return $this;
    }

    public function getPage()
    {
        return $this->page;
    }

    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }

    public function getRuku()
    {
        return $this->ruku;
    }

    public function setRuku($ruku)
    {
        $this->ruku = $ruku;

        return $this;
    }

    public function getHizbQuarter()
    {
        return $this->hizbQuarter;
    }

    public function setHizbQuarter($hizbQuarter)
    {
        $this->hizbQuarter = $hizbQuarter;

        return $this;
    }

    public function getSajda()
    {
        return $this->sajda;
    }

    public function setSajda($sajda)
    {
        $this->sajda = $sajda;

        return $this;
    }

    public function __toString()
    {
        return $this->getNumber();
    }
}