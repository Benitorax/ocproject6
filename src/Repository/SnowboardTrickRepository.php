<?php

namespace App\Repository;

use App\Entity\SnowboardTrick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SnowboardTrick|null find($id, $lockMode = null, $lockVersion = null)
 * @method SnowboardTrick|null findOneBy(array $criteria, array $orderBy = null)
 * @method SnowboardTrick[]    findAll()
 * @method SnowboardTrick[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SnowboardTrickRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SnowboardTrick::class);
    }

    // /**
    //  * @return SnowboardTrick[] Returns an array of SnowboardTrick objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SnowboardTrick
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @return SnowboardTrick[] Return 8 tricks for the given index.
     */
    public function findAlltricks(int $index = null)
    {
        return $this->createQueryBuilder('s')
            ->leftJoin('s.illustration', 'i')
            ->addSelect('i')
            ->orderBy('s.name', 'ASC')
            ->setFirstResult($index)
            ->setMaxResults(8)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return SnowboardTrick
     */
    public function findOneWithRelation(string $slug)
    {
        return $this->createQueryBuilder('s')
            ->leftJoin('s.illustration', 'i')
            ->addSelect('i')
            ->leftJoin('s.videos', 'v')
            ->addSelect('v')
            ->leftJoin('s.images', 'img')
            ->addSelect('img')
            ->andWhere('s.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
