<?php

namespace App\Repository\Webapp;

use App\Entity\Webapp\RessourceCat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RessourceCat|null find($id, $lockMode = null, $lockVersion = null)
 * @method RessourceCat|null findOneBy(array $criteria, array $orderBy = null)
 * @method RessourceCat[]    findAll()
 * @method RessourceCat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RessourceCatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RessourceCat::class);
    }

    // /**
    //  * @return RessourceCat[] Returns an array of RessourceCat objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RessourceCat
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
