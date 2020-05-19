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

    public function findByParams($params)
    {
        $query = $this->createQueryBuilder('p');

        if (isset($params['star']) && $params['star']) {
            $query->andWhere('p.path = :path')
                ->setParameter('path', 'stars/');
        }
        elseif (isset($params['star']) && !$params['star']) {
            $query->andWhere('p.path != :path')
                ->setParameter('path', 'stars/')
            ->andWhere('p.name = :name')
                ->setParameter('name', 'Name');

        }

        if (isset($params['public'])) {
            $query->andWhere('p.path = :path')
                ->setParameter('path', 'stars/')
                ->orWhere('p.name = :name')
                ->setParameter('name', 'Name');
        }

        $query->orderBy('p.category', 'asc')
            ->setMaxResults(1000);

        return $query->getQuery()->getResult();
    }
}
