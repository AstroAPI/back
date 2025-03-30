<?php
namespace App\Service;

use Psr\Log\LoggerInterface;

class HoroscopeService
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function displayDailyHoroscope()
    {
        // Logique pour récupérer et afficher l'horoscope du jour
        $horoscope = $this->getDailyHoroscope();
        $this->logger->info('Horoscope du jour: ' . $horoscope);
    }

    private function getDailyHoroscope(): string
    {
        // Implémentation pour récupérer l'horoscope du jour
        // Lien avec l'API à ajouter
        return 'Aujourd\'hui est un jour favorable pour les nouvelles entreprises.';
    }
}