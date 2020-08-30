<?php

namespace App\Repository;

use App\Entity\MapPointCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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
            ->getQuery()
            ->getArrayResult()
        ;
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
