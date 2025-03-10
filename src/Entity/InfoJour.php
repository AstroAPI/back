<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "info_jour")]
class InfoJour
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $date;

    #[ORM\Column(type: "string", length: 100)]
    private string $city;

    #[ORM\Column(type: "float")]
    private float $temperature;

    #[ORM\Column(type: "string", length: 50)]
    private string $weather;

    #[ORM\Column(type: "time")]
    private \DateTimeInterface $sunrise;

    #[ORM\Column(type: "time")]
    private \DateTimeInterface $sunset;

    #[ORM\Column(type: "string", length: 100)]
    private string $saintOfTheDay;

    // Getters et setters

    public function getId(): int
    {
        return $this->id;
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;
        return $this;
    }

    public function getTemperature(): float
    {
        return $this->temperature;
    }

    public function setTemperature(float $temperature): self
    {
        $this->temperature = $temperature;
        return $this;
    }

    public function getWeather(): string
    {
        return $this->weather;
    }

    public function setWeather(string $weather): self
    {
        $this->weather = $weather;
        return $this;
    }

    public function getSunrise(): \DateTimeInterface
    {
        return $this->sunrise;
    }

    public function setSunrise(\DateTimeInterface $sunrise): self
    {
        $this->sunrise = $sunrise;
        return $this;
    }

    public function getSunset(): \DateTimeInterface
    {
        return $this->sunset;
    }

    public function setSunset(\DateTimeInterface $sunset): self
    {
        $this->sunset = $sunset;
        return $this;
    }

    public function getSaintOfTheDay(): string
    {
        return $this->saintOfTheDay;
    }

    public function setSaintOfTheDay(string $saintOfTheDay): self
    {
        $this->saintOfTheDay = $saintOfTheDay;
        return $this;
    }
}
