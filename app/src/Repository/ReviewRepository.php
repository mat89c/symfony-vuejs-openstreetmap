<?php

namespace App\Repository;

use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\Query;

/**
 * @method Review|null find($id, $lockMode = null, $lockVersion = null)
 * @method Review|null findOneBy(array $criteria, array $orderBy = null)
 * @method Review[]    findAll()
 * @method Review[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewRepository extends ServiceEntityRepository
{
    private $limit = 30;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    public function getMapPointReviews(int $mapPointId, int $page)
    {
        $query = $this->createQueryBuilder('r')
            ->select('partial r.{id, content, rating, createdAt}', 'partial u.{id, name}', 'ri')
            ->innerJoin('r.user', 'u')
            ->leftJoin('r.reviewImages', 'ri')
            ->where('r.isActive = 1')
            ->andWhere('r.mapPoint = :mapPointId')
            ->setParameter('mapPointId', $mapPointId)
            ->setFirstResult($this->limit * ($page -1))
            ->setMaxResults($this->limit)
        ;

        $queryHydrateArray = $query->getQuery()->setHydrationMode(Query::HYDRATE_ARRAY);

        $paginator = new Paginator($queryHydrateArray, true);
        $iterator = $paginator->getIterator();

        /** @var ArrayIterator $iterator */
        return $iterator->getArrayCopy();
    }

    // /**
    //  * @return Review[] Returns an array of Review objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Review
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
