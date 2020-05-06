<?php

namespace App\Repository;

use App\Entity\Comic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comic|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comic|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comic[]    findAll()
 * @method Comic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComicRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comic::class);
    }

    /**
     * @param $value
     *
     * @return mixed
     */

    public function findByParams($params)
    {
        $query = $this->createQueryBuilder('c')
          ->orderBy('c.'. ($params['orderBy'] ?? 'id'), 'DESC')
          ->setMaxResults($params['limit'] ?? 100);

        if (isset($params['selected'])) {
            $query->andWhere('c.selected = 1');
        }

        return $query->getQuery()->getResult();
    }

    /**
     * @return int|mixed|string
     */
    public function findByHomepage()
    {
        return $this->createQueryBuilder('c')
          ->orderBy('c.dateSelected', 'DESC')
          ->setMaxResults(4)
          ->andWhere('c.selected = 1')
          ->getQuery()
          ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?Comic
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
