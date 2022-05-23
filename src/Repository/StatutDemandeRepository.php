<?php

namespace App\Repository;

use App\Entity\StatutDemande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StatutDemande|null find($id, $lockMode = null, $lockVersion = null)
 * @method StatutDemande|null findOneBy(array $criteria, array $orderBy = null)
 * @method StatutDemande[]    findAll()
 * @method StatutDemande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatutDemandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StatutDemande::class);
    }

    // /**
    //  * @return StatutDemande[] Returns an array of StatutDemande objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StatutDemande
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
