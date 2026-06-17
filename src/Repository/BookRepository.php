<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    //    /**
    //     * @return Book[] Returns an array of Book objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Book
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

       
   public function mafunc(Category $category):array
           {
                    return $this->createQueryBuilder('b')
                            ->join('b.categories','bc')
                            ->andWhere('bc =  :category')
                            ->setParameter('category',$category)
                            ->orderBy("b.title","ASC")
                            ->getQuery()
                            ->getResult();
           } 

  public function findbyStock(int $i)
  {
            return $this->createQueryBuilder('b')
                       ->andWhere('b.stock > :i')
                       ->setParameter('i',$i)
                       ->orderBy('b.title','ASC')
                       ->getQuery()
                       ->getResult();
  }     

}
