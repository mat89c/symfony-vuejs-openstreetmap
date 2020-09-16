<?php

namespace App\Repository;

use App\Entity\MapPointCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method MapPointCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method MapPointCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method MapPointCategory[]    findAll()
 * @method MapPointCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MapPointCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MapPointCategory::class);
    }

    public function getAllCategories()
    {
        return $this->createQueryBuilder('m')
            ->select('m.id', 'm.name')
            ->where('m.isActive = 1')
            ->orderBy('m.name', 'ASC')
            ->getQuery()
            ->getArrayResult()
        ;
    }

    public function countInactiveCategories()
    {
        return $this->createQueryBuilder('m')
            ->select('count(m.id)')
            ->where('m.isActive = 0')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function getAllTags(int $page, ?bool $status)
    {
        $itemsPerPage = 10;

        $query = $this->createQueryBuilder('c')
            ->select('c.id, c.name, c.isActive, count(m.id) as countMapPoint')
            ->leftJoin('c.mapPoints', 'm')
            ->orderBy('c.id', 'DESC')
            ->groupBy('c.id')
            ->setFirstResult($itemsPerPage * ($page -1))
            ->setMaxResults($itemsPerPage)
        ;

        if (!is_null($status)) {
            $query->andWhere('c.isActive = :status');
            $query->setParameter('status', $status);
        }

        $paginator = new Paginator($query, true);
        $paginator->setUseOutputWalkers(false);
        $totalItems = $paginator->count();

        return [
            'tags' => $query->getQuery()->getResult(),
            'itemsPerPage' => $itemsPerPage,
            'totalItems' => $totalItems,
        ];
    }

    public function getMapPointCategoryById(int $id)
    {
        return $this->createQueryBuilder('c')
            ->select('c.id, c.name, c.isActive, count(m.id) as countMapPoint')
            ->leftJoin('c.mapPoints', 'm')
            ->where('c.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult();
    }

    // /**
    //  * @return MapPointCategory[] Returns an array of MapPointCategory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MapPointCategory
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
