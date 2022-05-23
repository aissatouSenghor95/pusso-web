<?php

namespace App\Repository;

use App\Entity\TypeCommentaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypeCommentaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeCommentaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeCommentaire[]    findAll()
 * @method TypeCommentaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeCommentaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeCommentaire::class);
    }

    // /**
    //  * @return TypeCommentaire[] Returns an array of TypeCommentaire objects
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
    public function findOneBySomeField($value): ?TypeCommentaire
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
