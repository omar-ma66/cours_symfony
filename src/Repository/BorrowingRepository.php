<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\Borrowing;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Borrowing>
 */
class BorrowingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Borrowing::class);
    }

    //    /**
    //     * @return Borrowing[] Returns an array of Borrowing objects
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

    //    public function findOneBySomeField($value): ?Borrowing
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }


    /**
 * Retourne les emprunts actifs (non rendus) d'un utilisateur.
 */
public function findActiveByUser(User $user): array
{
    return $this->createQueryBuilder('b')
        ->andWhere('b.user = :user')
        ->andWhere('b.returnedAt IS NULL')
        ->setParameter('user', $user)
        ->orderBy('b.borrowedAt', 'DESC')
        ->getQuery()
        ->getResult();
}

/**
 * Vérifie si un utilisateur a déjà cet emprunt en cours.
 */
public function findOneActiveByUserAndBook(User $user, Book $book): ?Borrowing
{
    return $this->createQueryBuilder('b')
        ->andWhere('b.user = :user')
        ->andWhere('b.book = :book')
        ->andWhere('b.returnedAt IS NULL')
        ->setParameter('user', $user)
        ->setParameter('book', $book)
        ->getQuery()
        ->getOneOrNullResult();
}

public function findAllEmprunt(User $user):?array
{

        return $this->createQueryBuilder('b')
              ->andWhere('b.user = :user')
              ->andWhere('b.borrowedAt IS NOT NULL')
              ->setParameter('user',$user)
              ->orderBy('b.borrowedAt','DESC')
              ->getQuery()
              ->getResult();

}

public function findAllByUser():?array
{
           return $this->createQueryBuilder('b')
           ->andWhere('b.borrowedAt IS NOT NULL')
           ->orderBy('b.borrowedAt','DESC')
           ->getQuery() 
           ->getResult(); 
}


}
