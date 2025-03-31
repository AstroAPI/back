<?php
namespace App\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\JsonResponse;

class WeatherApiExceptionSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $weatherLogger;

    public function __construct(LoggerInterface $weatherLogger)
    {
        $this->weatherLogger = $weatherLogger;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException', 10],
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $request = $event->getRequest();
        
        // On ne s'occupe que des requêtes liées aux horoscopes
        if (str_contains($request->getPathInfo(), '/api/horoscope')) {
            $exception = $event->getThrowable();
            
            $this->weatherLogger->error('Erreur API météo: ' . $exception->getMessage(), [
                'path' => $request->getPathInfo(),
                'query' => $request->query->all(),
                'exception' => get_class($exception)
            ]);
            
            $event->setResponse(new JsonResponse([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la génération de l\'horoscope.'
            ], 500));
        }
    }
}