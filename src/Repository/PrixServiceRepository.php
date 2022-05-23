<?php

namespace App\Repository;

use App\Entity\PrixService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PrixService|null find($id, $lockMode = null, $lockVersion = null)
 * @method PrixService|null findOneBy(array $criteria, array $orderBy = null)
 * @method PrixService[]    findAll()
 * @method PrixService[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrixServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PrixService::class);
    }

    // /**
    //  * @return PrixService[] Returns an array of PrixService objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PrixService
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
