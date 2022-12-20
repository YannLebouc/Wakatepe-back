<?php

namespace App\Repository;

use App\Entity\Offer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends ServiceEntityRepository<Offer>
 *
 * @method Offer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offer[]    findAll()
 * @method Offer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OfferRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offer::class);
    }

    public function add(Offer $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Offer $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    /**
     * Retrieves a users inactive offers
     * @param [id] $id
     * @return array
     */

    public function findUserInactiveOffers($id) : array
    {   
        $query = $this->getEntityManager()->createQuery(
            "SELECT o FROM App\Entity\Offer o
            JOIN o.user u
            WHERE u.id = $id 
            AND o.isActive = false
            ");

        return $query->getResult();
    }
    
     /**
     * Retrieves a categories active offers
     *
     * @param [id] $id
     * @return array
     */
    public function findActiveOffers($id) : array
    {   
        $query = $this->getEntityManager()->createQuery(
            "SELECT o FROM App\Entity\Offer o
            JOIN o.categories c
            WHERE c.id = $id 
            AND o.isActive = true
            ");

        return $query->getResult();
    }

    /**
     * Retrieves all the offers containing a keyword in their title
     *
     * @param [mixed] $keyword
     * @return void
     */
    public function findSearchedOffers($keyword)
    {
        
        $qb = $this->getEntityManager()->createQueryBuilder();
        
        $qb->select('o')
            ->from('App\Entity\Offer', 'o')
            ->where($qb->expr()->like('o.title', ':title'))
            ->setParameter('title', '%'.$keyword.'%'
        );
            
        $query = $qb->getQuery();
        return $query->getResult();

        // $query = $this->getEntityManager()->createQuery('SELECT o
        // FROM App\Entity\Offer o
        // WHERE o.title LIKE :title
        // ');
        // $query->setParameter('title', '%'.$keyword.'%');
        // return $query->getResult();
    }

    /**
     * Retrieves a list of reported offers
     *
     * @return array
     */
    public function reportedOffers() : array
    {   
        $query = $this->getEntityManager()->createQuery(
            "SELECT o FROM App\Entity\Offer o
            WHERE o.isReported = true
            ");

        return $query->getResult();
    }

//    /**
//     * @return Offer[] Returns an array of Offer objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Offer
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
