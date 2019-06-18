<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function findAllWithCategories()
    {
       $qb = $this->createQueryBuilder('a')
           ->innerJoin('a.category', 'c' )
           ->addSelect('c')
           ->getQuery();

       return $qb->execute();
    }

    public function findAllWithCategoriesAndTagsDQL()
    {
       $em = $this->getEntityManager();
       $query = $em->createQuery('SELECT a, c, t FROM App\Entity\Article a LEFT JOIN a.category c LEFT JOIN a.tags t');

       return $query->execute();
    }

    public function findAllWithCategoriesAndTags()
    {
       $qb = $this->createQueryBuilder('article')
            ->leftJoin('article.category', 'category')
            ->leftJoin('article.tags', 'tags')
            ->addSelect('category')
            ->addSelect('tags')
            ->getQuery();

        return $qb->execute();    
    }

        public function findAllWithCategoriesAndTagsFC()
    {
        $qb = $this->createQueryBuilder('a')
            ->leftJoin('a.tags', 't')
            ->leftJoin('a.category', 'c' )
            ->addSelect('t')
            ->addSelect('c')
            ->getQuery();
        return $qb->execute();
    }        

    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
