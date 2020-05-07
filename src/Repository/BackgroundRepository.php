<?php

namespace App\Repository;

use App\Entity\Background;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Background|null find($id, $lockMode = null, $lockVersion = null)
 * @method Background|null findOneBy(array $criteria, array $orderBy = null)
 * @method Background[]    findAll()
 * @method Background[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BackgroundRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Background::class);
    }

    public function findByCriteria(array $criteria)
    {
        $query = $this->createQueryBuilder('b')
          ->where('1 = 1');

        if (isset($criteria['selection'])) {
            $query->andWhere('b.selection = 1');
        }

        if (isset($criteria['image'])) {
            $query->andWhere('b.color is NULL')
              ->andWhere('b.css is NULL');
        }
        elseif (isset($criteria['color'])) {

        }

        return $query->orderBy('b.id', 'ASC')
            ->setMaxResults($criteria['limit'] ?? 100)
            ->getQuery()
            ->getResult();
    }

}
