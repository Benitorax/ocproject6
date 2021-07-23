<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\ORM\Query;
use App\Entity\SnowboardTrick;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    // /**
    //  * @return Comment[] Returns an array of Comment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Comment
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * Returns an array of comments of the given trick.
     *
     * @return Comment[]
     */
    public function findBySnowboardTrick(SnowboardTrick $trick)
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.user', 'u')
            ->addSelect('u')
            ->andWhere('c.snowboardTrick = :trick')
            ->setParameter('trick', $trick)
            ->orderBy('c.createdAt', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Returns a Query object for comments of the given trick.
     *
     * @return Query
     */
    public function findBySnowboardTrickQuery(SnowboardTrick $trick)
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.user', 'u')
            ->addSelect('u')
            ->andWhere('c.snowboardTrick = :trick')
            ->setParameter('trick', $trick)
            ->orderBy('c.createdAt', 'ASC')
            ->getQuery()
        ;
    }
}
