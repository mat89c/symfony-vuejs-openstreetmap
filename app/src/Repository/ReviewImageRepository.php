<?php

namespace App\Repository;

use App\Entity\ReviewImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReviewImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReviewImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReviewImage[]    findAll()
 * @method ReviewImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReviewImage::class);
    }

    // /**
    //  * @return ReviewImage[] Returns an array of ReviewImage objects
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
    public function findOneBySomeField($value): ?ReviewImage
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
