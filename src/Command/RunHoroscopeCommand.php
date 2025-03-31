<?php

namespace App\Command;

use App\Service\HoroscopeService;
use App\Service\HoroscopeMailer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunHoroscopeCommand extends Command
{
    private HoroscopeService $horoscopeService;
    private HoroscopeMailer $horoscopeMailer;

    public function __construct(HoroscopeService $horoscopeService, HoroscopeMailer $horoscopeMailer)
    {
        $this->horoscopeService = $horoscopeService;
        $this->horoscopeMailer = $horoscopeMailer;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('app:run-horoscope')
             ->setDescription('Affiche l\'horoscope du jour et l\'envoie par email.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Récupérer l'horoscope (remplace par un appel réel à ton service)
        $horoscope = "Vous allez avoir une journée incroyable !";
        $meteo = "Aujourd'hui, le temps sera ensoleillé avec une température de 20°C.";

        // Afficher dans la console
        $output->writeln('📢 Horoscope du jour : ' . $horoscope);
        $output->writeln('🌤️ Météo du jour : ' . $meteo);

        // Envoi de l'email
        $output->writeln('📩 Envoi de l\'email...');
        $this->horoscopeMailer->sendDailyHoroscope('test@mailtrap.io');
        $output->writeln('✅ Email envoyé avec succès !');

        return Command::SUCCESS;
    }
}
