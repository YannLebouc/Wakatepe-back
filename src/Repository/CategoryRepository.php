<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 *
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function add(Category $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Category $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }



    public function findAllAdvertisements($id)
    {   
        $query = $this->getEntityManager()->createQuery("SELECT c, o, w
        FROM App\Entity\Category c
        JOIN c.offer o
        JOIN c.wish w
        WHERE c.id = $id
        AND o.isActive = 1
        AND w.isActive = 1
        ");
        $categoryAdvertisements = $query->getResult();
        return $categoryAdvertisements;
    }

    /**
     * 
     */
    public function findAllOffers($id)
    {   
        $query = $this->getEntityManager()->createQuery('SELECT c, o
        FROM App\Entity\Category c
        JOIN c.offer o
        WHERE c.isActive = true
        AND o.isActive = true
        AND o.isLended = false 
        AND c.id = '. $id .'
        ');
        $categoryOffers = $query->getResult();
        return $categoryOffers;
    }

    /**
     * 
     */
    public function findAllWishes($id)
    {   
        $query = $this->getEntityManager()->createQuery('SELECT c, w
        FROM App\Entity\Category c
        JOIN c.wish w
        WHERE c.isActive = true
        AND w.isActive = true
        AND c.id = '. $id .'
        ');
        $categoryOffers = $query->getResult();
        return $categoryOffers;
    }


    /**
     * 
     */
    public function findAllActiveCategories()
    {   
        // on veut la liste des catégories où isActive = true
        $query = $this->getEntityManager()->createQuery('SELECT c
        FROM App\Entity\Category c
        WHERE c.isActive = true
        ');
        $activeCategories = $query->getResult();
        return $activeCategories;
    }


//    public function findOneBySomeField($value): ?Category
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
