<?php

namespace App\Repository\Webapp;

use App\Entity\Webapp\Ressources;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ressources|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ressources|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ressources[]    findAll()
 * @method Ressources[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RessourcesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ressources::class);
    }

    public function ListByCategory($ressourcecat): ?Ressources
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.category', 'rc')
            ->andWhere('rc.id = :category')
            ->setParameter('category', $ressourcecat)
            ->getQuery()
            ->getResult()
            ;
    }

    public function ListFilters($filters)
    {
        $query = $this->createQueryBuilder('r');
        if($filters != null){
            $query->andWhere('r.category IN(:cats)')
                ->setParameter(':cats', array_values($filters));
        }
        return $query->getQuery()->getResult();
    }

    // /**
    //  * @return Ressources[] Returns an array of Ressources objects
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
    public function findOneBySomeField($value): ?Ressources
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
