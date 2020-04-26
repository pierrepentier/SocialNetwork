<?php

namespace App\Repository;

use App\Entity\NotifiedFriends;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NotifiedFriends|null find($id, $lockMode = null, $lockVersion = null)
 * @method NotifiedFriends|null findOneBy(array $criteria, array $orderBy = null)
 * @method NotifiedFriends[]    findAll()
 * @method NotifiedFriends[]    findByNotificationsClassedByDate($user)
 * @method NotifiedFriends[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotifiedFriendsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NotifiedFriends::class);
    }

    // /**
    //  * @return NotifiedFriends[] Returns an array of NotifiedFriends objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NotifiedFriends
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

         /**
      * @return NotifiedFriends[] Returns an array of NotifiedFriends objects
      */
      public function findByNotificationsClassedByDate($user)
      {
          $entityManager = $this->getEntityManager();
          $query = $entityManager->createQuery("SELECT n FROM App\Entity\NotifiedFriends n
                                                JOIN n.notification u WHERE n.friend = :user
                                                ORDER BY u.date DESC")
                                  ->setParameter('user', $user);
          return $query->getResult();
      }
}
