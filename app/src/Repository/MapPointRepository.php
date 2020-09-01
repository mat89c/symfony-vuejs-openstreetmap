<?php

namespace App\Repository;

use App\Entity\MapPoint;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * @method MapPoint|null find($id, $lockMode = null, $lockVersion = null)
 * @method MapPoint|null findOneBy(array $criteria, array $orderBy = null)
 * @method MapPoint[]    findAll()
 * @method MapPoint[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MapPointRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MapPoint::class);
    }

    public function getAllMapPoints(?array $checkedCategories)
    {
        $query =  $this->createQueryBuilder('m')
            ->select('partial m.{id, title, description, lat, lng, color, logo, uploadDir}', 'partial u.{id}', 'partial c.{id, name}')
            ->innerJoin('m.user', 'u')
            ->leftJoin('m.mapPointCategories', 'c', 'WITH', 'c.isActive = 1')
            ->where('m.isActive = 1')
        ;

        if (!empty($checkedCategories)) {
            $query->andWhere('c.id IN(:categories)');
            $query->setParameter('categories', $checkedCategories);
        }

        return $query->getQuery()->getArrayResult();
    }

    public function getMapPointById(int $id)
    {
        return $this->createQueryBuilder('m')
            ->select('m', 'i', 'partial u.{id, name}', 'partial c.{id, name}', 'partial r.{id, content, rating, createdAt}', 'partial ru.{id, name}')
            ->innerJoin('m.user', 'u')
            ->leftJoin('m.mapPointImage', 'i')
            ->leftJoin('m.mapPointCategories', 'c', 'WITH', 'c.isActive = 1')
            ->leftJoin('m.reviews', 'r', 'WITH', 'r.isActive = 1')
            ->leftJoin('r.user', 'ru')
            ->where('m.id = :id')
            ->andWhere('m.isActive = 1')
            ->setParameter('id', $id)
            ->getQuery()
            ->getArrayResult()
        ;
    }

    // /**
    //  * @return MapPoint[] Returns an array of MapPoint objects
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
    public function findOneBySomeField($value): ?MapPoint
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
