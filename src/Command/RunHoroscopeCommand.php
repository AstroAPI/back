<?php

namespace App\Command;

use App\Service\HoroscopeService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunHoroscopeCommand extends Command
{
    private HoroscopeService $horoscopeService;

    public function __construct(HoroscopeService $horoscopeService)
    {
        $this->horoscopeService = $horoscopeService;
        parent::__construct();
    }

    protected function configure()
    {
        // Assurez-vous que la commande a un nom valide
        $this->setName('app:run-horoscope')  // Nom de la commande
             ->setDescription('Affiche l\'horoscope du jour');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Appel de ton service pour afficher l'horoscope du jour
        $this->horoscopeService->displayDailyHoroscope();

        // Affichage dans la sortie de la commande
        $output->writeln('L\'horoscope du jour a été affiché avec succès.');

        // Retourner un code de succès (0)
        return Command::SUCCESS;
    }
}
