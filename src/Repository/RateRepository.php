<?php

namespace App\Repository;

use App\Entity\Rate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Rate|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rate|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rate[]    findAll()
 * @method Rate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rate::class);
    }

    public function findOneByCriteria(array $criteria = null, array $orderBy = null)
    {
        $criteria['limit'] = 1;
        return $this->findAllByCriteria($criteria, $orderBy);
    }

    public function findAllByCriteria(array $criteria = null, array $orderBy = null) {
        $qb = $this->_em->createQueryBuilder();

        if(isset($criteria['get_count'])) {
            $qb->select('count(b) as c');

        }
        else {
            $qb->select(array('b'));
        }

        $qb->from('App:Rate', 'b');

        if(isset($criteria['dateCreation'])) {

            $date = date('Y-m-d', strtotime( $criteria['dateCreation'] ) );

            $qb->andWhere('b.dateCreation LIKE :dateCreation')
              ->setParameter('dateCreation', $date .'%');
        }

        if(isset($criteria['user'])) {
            $qb->andWhere('b.user = :user')
              ->setParameter('user', $criteria['user']);
        }

        if(isset($criteria['comic'])) {
            $qb->andWhere('b.comic = :comic')
              ->setParameter('comic', $criteria['comic']);
        }

        if(isset($criteria['get_count'])) {
            $count = $qb->getQuery()->getResult();
            return (int)$count[0]['c'];
        }


        if(isset($orderBy)) {
            $qb->add('orderBy', 'b.'. $orderBy['field'] .' '. $orderBy['sort'] ?? 'ASC');
        }
        else {
            $qb->add('orderBy', 'b.id ASC');
        }

        if(isset($criteria['offset'])) {
            $qb->setFirstResult($criteria['offset']);
        }
        if(isset($criteria['limit'])) {
            $qb->setMaxResults($criteria['limit']);
        }

        if(isset($criteria['query'])){
            return $qb;
        }


        if(isset($criteria['limit']) and $criteria['limit'] == 1) {
            try {
                return $qb->getQuery()->getSingleResult();
            } catch (\Doctrine\ORM\NoResultException $e) {
                return null;
            }
        }
        else {
            return $qb->getQuery()->getResult();
        }

    }
}
