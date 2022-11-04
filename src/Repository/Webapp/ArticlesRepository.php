<?php

namespace App\Repository\Webapp;

use App\Entity\Webapp\Articles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Articles|null find($id, $lockMode = null, $lockVersion = null)
 * @method Articles|null findOneBy(array $criteria, array $orderBy = null)
 * @method Articles[]    findAll()
 * @method Articles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticlesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Articles::class);
    }

    public function listArticlesBySection($idsection)
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.sections', 's')
            ->leftJoin('a.college', 'c')
            ->leftJoin('a.theme', 't')
            ->leftJoin('a.support' , 'su')
            ->addSelect('
                a.id as id, 
                a.slug as slug, 
                a.title as title, 
                a.isTitleShow, 
                a.isShowReadMore, 
                a.content as content,
                a.isArchived as isArchived,
                a.isShowCreated as isShowCreated,
                t.id as idtheme, 
                t.name as theme, 
                a.imageName, 
                a.createdAt,
                su.id as idsupport, 
                su.name as support,
                c.id AS idcollege
                '
            )
            ->andWhere('s.id = :idsection')
            ->andWhere('a.isArchived = :isArchived')
            ->setParameter('idsection', $idsection)
            ->setParameter('isArchived', 0)
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function listArticlesByColleges($idsection)
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.section', 's')
            ->andWhere('a.college > 0')
            ->andWhere('a.isArchived = :isArchived')
            ->setParameter('isArchived', 0)
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function listArticlesByCollege($idcollege)
    {
        return $this->createQueryBuilder('a')
            ->addSelect('
                a.id as id, 
                a.slug, 
                a.title as title,
                a.content as content, 
                t.id as idtheme, 
                t.name as theme, 
                a.imageName,
                a.createdAt,
                a.updatedAt, 
                s.id as idsupport, 
                s.name as support,
                c.id AS idcollege
                ')
            ->leftJoin('a.college', 'c')
            ->leftJoin('a.theme', 't')
            ->leftJoin('a.support' , 's')
            ->andWhere('c.id = :idcollege')
            ->setParameter('idcollege', $idcollege)
            ->andWhere('a.isArchived = :isArchived')
            ->setParameter('isArchived', 0)
            ->orderBy('a.updatedAt', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function listFiveArticles($category)
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.college', 'c')
            ->leftJoin('a.author', 'u')
            ->addSelect('
                a.id as id, 
                a.slug as slug, 
                a.title as title,
                a.content as content,
                a.imageName,
                a.updatedAt,
                c.id AS idcollege,
                u.type
                 ')
            ->where('u.type = :type')
            ->setParameter('type', 'college')
            ->orderBy('a.updatedAt', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param $slug
     * @return int|mixed|string|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     * Affiche un articel selon son slug
     */
    public function articlecollegeSlug($id)
    {
        return $this->createQueryBuilder('a')
            ->addSelect('
                a.id as id, 
                a.slug, 
                a.title as title, 
                a.content as content, 
                a.doc, 
                a.isArchived as isArchived,
                a.isShowCreated as isShowCreated,
                t.id as idtheme, 
                t.name as theme, 
                a.imageName, 
                a.isTitleShow, 
                a.intro, 
                a.isShowIntro,
                a.createdAt As createdAt,
                c.name, c.id AS idcollege,
                c.headerName, 
                c.logoName, 
                c.GroupDescription,
                a.isShowReadMore, 
                s.id as idsupport, 
                s.name as support
                 ')
            ->leftJoin('a.college', 'c')
            ->leftJoin('a.theme', 't')
            ->leftJoin('a.support' , 's')
            ->leftJoin('a.category', 'ca')
            ->andWhere('a.isArchived = :isArchived')
            ->setParameter('isArchived', 0)
            ->andWhere('a.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
