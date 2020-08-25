<?php

namespace App\Repository;

use App\Entity\MapPointImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MapPointImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method MapPointImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method MapPointImage[]    findAll()
 * @method MapPointImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MapPointImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MapPointImage::class);
    }

    // /**
    //  * @return MapPointImage[] Returns an array of MapPointImage objects
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
    public function findOneBySomeField($value): ?MapPointImage
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
