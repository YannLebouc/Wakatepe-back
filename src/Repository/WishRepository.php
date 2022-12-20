<?php

namespace App\Repository;

use App\Entity\Wish;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Wish>
 *
 * @method Wish|null find($id, $lockMode = null, $lockVersion = null)
 * @method Wish|null findOneBy(array $criteria, array $orderBy = null)
 * @method Wish[]    findAll()
 * @method Wish[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WishRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Wish::class);
    }

    public function add(Wish $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Wish $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    /**
     * Retrieves a users inactive wishes
     * @param [id] $id
     * @return array
     */


    public function findUserInactiveWishes($id) : array
    {   
        $query = $this->getEntityManager()->createQuery(
            "SELECT w FROM App\Entity\Wish w
            JOIN w.user u
            WHERE u.id = $id 
            AND w.isActive = false
            ");
            
        return $query->getResult();
    }
    
     /**
     * Retrieves a categories active wishes
     *
     * @param [id] $id
     * @return array
     */
    public function findActiveWishes($id) : array
    {   
        $query = $this->getEntityManager()->createQuery(
            "SELECT w FROM App\Entity\Wish w
            JOIN w.categories c
            WHERE c.id = $id 
            AND w.isActive = true
            ");

        return $query->getResult();
    }
    
     /**
     * Retrieves a users active wishes
     *
     * @param [id] $id
     * @return array
     */
    public function findUserActiveWishes($id) : array
    {   
        $query = $this->getEntityManager()->createQuery(
            "SELECT w FROM App\Entity\Wish w
            JOIN w.user u
            WHERE u.id = $id 
            AND w.isActive = true
            ");

        return $query->getResult();
    }


        /**
     * Retrieves a list of reported wishes
     *
     * @return array
     */
    public function reportedWishes() : array
    {   
        $query = $this->getEntityManager()->createQuery(
            "SELECT w FROM App\Entity\Wish w
            WHERE w.isReported = true
            ");
      
        return $query->getResult();
    }



    /**
     * Retrieves all the wishes containing a keyword in their title
     *
     * @param [mixed] $keyword
     * @return void
     */
    public function findSearchedWishes($keyword)
    {
        
        $qb = $this->getEntityManager()->createQueryBuilder();
        
        $qb->select('w')
            ->from('App\Entity\Wish', 'w')
            ->where($qb->expr()->like('w.title', ':title'))
            ->setParameter('title', '%'.$keyword.'%'
        );
            
        $query = $qb->getQuery();

        return $query->getResult();
    }

//    /**
//     * @return Wish[] Returns an array of Wish objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('w.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Wish
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
