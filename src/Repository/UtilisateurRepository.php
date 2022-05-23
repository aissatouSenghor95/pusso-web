<?php

namespace App\Repository;

use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Utilisateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Utilisateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Utilisateur[]    findAll()
 * @method Utilisateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UtilisateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utilisateur::class);
    }

    public function loadUserByUsername($login)
    {
        return $this->createQueryBuilder('u')
            ->where('u.username = :username')
            ->setParameter('username', $login)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getSysUser(): Utilisateur
    {
        return $this->createQueryBuilder('u')
            ->where('u.id = 1')
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getFinalClients(){
        return $this->createQueryBuilder('u')
            ->andWhere('u.role = 5')
            ->getQuery()
            ->getResult()
            ;
    }
}
