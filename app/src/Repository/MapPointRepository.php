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

    public function getAllMapPoints()
    {
        return $this->createQueryBuilder('m')
            ->select('m.id', 'u.id as userId', 'm.title', 'm.description', 'm.lat', 'm.lng', 'm.color', 'm.logo', 'm.uploadDir')
            ->innerJoin('m.user', 'u')
            ->where('m.isActive = 1')
            ->getQuery()
            ->getResult()
        ;
    }

    public function getMapPointById(int $id)
    {
        return $this->createQueryBuilder('m')
            ->select('m', 'i', 'partial u.{id}')
            ->innerJoin('m.user', 'u')
            ->leftJoin('m.mapPointImage', 'i')
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
