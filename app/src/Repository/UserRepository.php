<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function countInactiveUsers()
    {
        return $this->createQueryBuilder('u')
            ->select('count(u.id)')
            ->where('u.isActive = 0')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function getAllUsers(int $page, ?bool $status)
    {
        $itemsPerPage = 10;

        $query = $this->createQueryBuilder('u')
            ->select('u.id, u.name, u.email, u.roles, u.isActive')
            ->addSelect('(SELECT count(m.id) FROM App\Entity\MapPoint m WHERE u.id = m.user) as countMapPoint')
            ->addSelect('(SELECT count(r.id) FROM App\Entity\Review r WHERE u.id = r.user) as countReview')
            ->where('u.email != :admin')
            ->setParameter('admin', 'admin@example.com')
            ->orderBy('u.id', 'DESC')
            ->setFirstResult($itemsPerPage * ($page -1))
            ->setMaxResults($itemsPerPage)
        ;

        if (!is_null($status)) {
            $query->andWhere('u.isActive = :status');
            $query->setParameter('status', $status);
        }

        $paginator = new Paginator($query, true);
        $paginator->setUseOutputWalkers(false);
        $totalItems = $paginator->count();

        return [
            'users' => $query->getQuery()->getResult(),
            'itemsPerPage' => $itemsPerPage,
            'totalItems' => $totalItems,
        ];
    }

    public function searchUserByIdOrEmail(string $value)
    {
        return $this->createQueryBuilder('u')
            ->select('u.id, u.name, u.email')
            ->where('u.id LIKE :value OR u.email LIKE :value')
            ->setMaxResults(5)
            ->setParameter('value', '%'.$value.'%')
            ->getQuery()
            ->getArrayResult();
    }

    public function getUserById(int $id)
    {
        return $this->createQueryBuilder('u')
            ->select('u.id, u.name, u.email, u.roles, u.isActive')
            ->addSelect('(SELECT count(m.id) FROM App\Entity\MapPoint m WHERE u.id = m.user) as countMapPoint')
            ->addSelect('(SELECT count(r.id) FROM App\Entity\Review r WHERE u.id = r.user) as countReview')
            ->where('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult()
        ;
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
