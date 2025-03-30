<?php

namespace App\Repository;

use App\Entity\HoroscopeTemplate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HoroscopeTemplate>
 */
class HoroscopeTemplateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HoroscopeTemplate::class);
    }

    public function findBySignAndWeather(string $sign, string $condition, string $tempRange): ?HoroscopeTemplate
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.sign = :sign')
            ->andWhere('h.weatherCondition = :condition')
            ->andWhere('h.temperatureRange = :tempRange')
            ->setParameter('sign', $sign)
            ->setParameter('condition', $condition)
            ->setParameter('tempRange', $tempRange)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findDefaultTemplate(string $sign): ?HoroscopeTemplate
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.sign = :sign')
            ->andWhere('h.weatherCondition = :condition')
            ->setParameter('sign', $sign)
            ->setParameter('condition', 'default')
            ->getQuery()
            ->getOneOrNullResult();
    }
}