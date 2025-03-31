<?php

namespace App\Entity;

use App\Repository\HoroscopeTemplateRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HoroscopeTemplateRepository::class)]
class HoroscopeTemplate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $sign = null;

    #[ORM\Column(length: 50)]
    private ?string $weatherCondition = null;

    #[ORM\Column(length: 20)]
    private ?string $temperatureRange = null;

    #[ORM\Column(type: 'text')]
    private ?string $template = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSign(): ?string
    {
        return $this->sign;
    }

    public function setSign(string $sign): static
    {
        $this->sign = $sign;
        return $this;
    }

    public function getWeatherCondition(): ?string
    {
        return $this->weatherCondition;
    }

    public function setWeatherCondition(string $weatherCondition): static
    {
        $this->weatherCondition = $weatherCondition;
        return $this;
    }

    public function getTemperatureRange(): ?string
    {
        return $this->temperatureRange;
    }

    public function setTemperatureRange(string $temperatureRange): static
    {
        $this->temperatureRange = $temperatureRange;
        return $this;
    }

    public function getTemplate(): ?string
    {
        return $this->template;
    }

    public function setTemplate(string $template): static
    {
        $this->template = $template;
        return $this;
    }
}