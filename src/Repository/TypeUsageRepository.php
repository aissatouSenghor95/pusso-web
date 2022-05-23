<?php

namespace App\Repository;

use App\Entity\TypeUsage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypeUsage|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeUsage|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeUsage[]    findAll()
 * @method TypeUsage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeUsageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeUsage::class);
    }

    // /**
    //  * @return TypeUsage[] Returns an array of TypeUsage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TypeUsage
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
