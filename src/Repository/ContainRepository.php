<?php

namespace App\Repository;

use App\Entity\Contain;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Contain|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contain|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contain[]    findAll()
 * @method Contain[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContainRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contain::class);
    }

    // /**
    //  * @return Contain[] Returns an array of Contain objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Contain
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
