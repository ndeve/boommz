<?php

namespace App\Repository;

use App\Entity\Comic;
use App\Entity\Persona;
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
    public function findByParams(array $params)
    {
        $query = $this->createQueryBuilder('c');

        if(isset($params['get_count'])) {
            $query->select('count(c) as count');
        }
        else {
            $query->select();
        }

        if (isset($params['selected'])) {
            $query->andWhere('c.selected = 1');
        }

        if (isset($params['author'])) {
            $query->andWhere('c.author = :author')
                ->setParameter('author', $params['author']);
        }

        if (isset($params['draft'])) {
            $query->andWhere('c.datePublication IS NULL');
        }
        else {
            $query->andWhere('c.datePublication IS NOT NULL');
        }

        if(isset($params['get_count'])) {
            $count = $query->getQuery()->getResult();
            return (int)$count[0]['count'];
        }

        $query->orderBy('c.'. ($params['orderBy'] ?? 'id'), 'DESC')
            ->setFirstResult($params['offset'] ?? 0)
            ->setMaxResults($params['limit'] ?? 100);

        return $query->getQuery()->getResult();
    }

    /**
     * @param $value
     *
     * @return mixed
     */
    public function findFromPerso(Persona $perso)
    {
        $query = $this->createQueryBuilder('c');

	    $query->leftJoin('c.pages', 'page')
	    ->leftJoin('page.boxes', 'box')
	    ->leftJoin('box.bubbles', 'bubble')
	    ->leftJoin('bubble.persona', 'persona')
	    ->where('persona.id = :id')
	    ->setParameter('id', $perso->getId());


        $query->andWhere('c.datePublication IS NOT NULL')
	        ->orderBy('c.'. ($params['orderBy'] ?? 'id'), 'DESC')
            ->setFirstResult($params['offset'] ?? 0)
            ->setMaxResults($params['limit'] ?? 100);

        return $query->getQuery()->getResult();
    }
}
