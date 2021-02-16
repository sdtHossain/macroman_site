<?php

namespace App\Repository;

use App\Entity\JobPost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method JobPost|null find($id, $lockMode = null, $lockVersion = null)
 * @method JobPost|null findOneBy(array $criteria, array $orderBy = null)
 * @method JobPost[]    findAll()
 * @method JobPost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobPostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JobPost::class);
    }

    // /**
    //  * @return JobPost[] Returns an array of JobPost objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?JobPost
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
