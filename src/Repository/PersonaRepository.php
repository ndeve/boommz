<?php

namespace App\Repository;

use App\Entity\Persona;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Persona|null find($id, $lockMode = null, $lockVersion = null)
 * @method Persona|null findOneBy(array $criteria, array $orderBy = null)
 * @method Persona[]    findAll()
 * @method Persona[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Persona::class);
    }

    public function findAllStars()
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.path = :path')
            ->setParameter('path', 'stars/')
            ->orWhere('p.name = :name')
            ->setParameter('name', 'Name')
            ->orderBy('p.path', 'desc')
            ->setMaxResults(1000)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllPublic()
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.path = :path')
            ->setParameter('path', 'stars/')
            ->orderBy('p.path', 'desc')
            ->setMaxResults(1000)
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?Persona
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
