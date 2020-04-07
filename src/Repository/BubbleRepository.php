<?php

namespace App\Repository;

use App\Entity\Bubble;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Bubble|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bubble|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bubble[]    findAll()
 * @method Bubble[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BubbleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bubble::class);
    }

    // /**
    //  * @return Bubble[] Returns an array of Bubble objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Bubble
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
