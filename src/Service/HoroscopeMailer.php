<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class HoroscopeMailer
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendDailyHoroscope(string $recipientEmail): void
    {
        $horoscope = "Vous allez avoir une journée incroyable !";
        $meteo = "Aujourd'hui, le temps sera ensoleillé avec une température de 20°C.";

        $email = (new Email())
            ->from('noreply@votre-site.com')
            ->to($recipientEmail)
            ->subject("Votre horoscope et météo du jour")
            ->html("<h1>Horoscope du jour</h1><p>$horoscope</p><h2>Météo</h2><p>$meteo</p>");

        $this->mailer->send($email);
    }
}