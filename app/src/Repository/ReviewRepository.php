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
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    public function getMapPointReviews(int $mapPointId, int $page)
    {
        $itemsPerPage = 30;
        $query = $this->createQueryBuilder('r')
            ->select('partial r.{id, content, rating, createdAt}', 'partial u.{id, name}', 'ri')
            ->innerJoin('r.user', 'u')
            ->leftJoin('r.reviewImages', 'ri')
            ->where('r.isActive = 1')
            ->andWhere('r.mapPoint = :mapPointId')
            ->setParameter('mapPointId', $mapPointId)
            ->setFirstResult($this->itemsPerPage * ($page -1))
            ->setMaxResults($this->itemsPerPage)
        ;

        $queryHydrateArray = $query->getQuery()->setHydrationMode(Query::HYDRATE_ARRAY);

        $paginator = new Paginator($queryHydrateArray, true);
        $iterator = $paginator->getIterator();

        /** @var ArrayIterator $iterator */
        return $iterator->getArrayCopy();
    }

    public function countInactiveReviews()
    {
        return $this->createQueryBuilder('r')
            ->select('count(r.id)')
            ->where('r.isActive = 0')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function getAllReviews(int $page, ?bool $status)
    {
        $itemsPerPage = 10;
        $query = $this->createQueryBuilder('r')
            ->select('partial r.{id, content, rating, createdAt, isActive}, partial u.{id, name}, partial m.{id, title}')
            ->innerJoin('r.user', 'u')
            ->innerJoin('r.mapPoint', 'm')
            ->setFirstResult($itemsPerPage * ($page -1))
            ->setMaxResults($itemsPerPage)
        ;

        if (!is_null($status)) {
            $query->andWhere('r.isActive = :status');
            $query->setParameter('status', $status);
        }

        $queryHydrateArray = $query->getQuery()->setHydrationMode(Query::HYDRATE_ARRAY);


        $paginator = new Paginator($queryHydrateArray, true);
        $iterator = $paginator->getIterator();
        $totalItems = $paginator->count();

         /** @var ArrayIterator $iterator */
         $reviews = $iterator->getArrayCopy();
        return [
            'reviews' => $reviews,
            'itemsPerPage' => $itemsPerPage,
            'totalItems' => $totalItems,
        ];
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
