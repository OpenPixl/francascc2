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
            ->select('a.id as id, a.slug, a.title as title, a.isTitleShow, a.isShowReadMore, a.content as content, t.id as idtheme, t.name as theme, a.imageName, su.id as idsupport, su.name as support')
            ->leftJoin('a.sections', 's')
            ->leftJoin('a.college', 'c')
            ->leftJoin('a.theme', 't')
            ->leftJoin('a.support' , 'su')
            ->andWhere('s.id = :idsection')
            ->setParameter('idsection', $idsection)
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
                 a.updatedAt, 
                 s.id as idsupport, 
                 s.name as support
                 ')
            ->leftJoin('a.college', 'c')
            ->leftJoin('a.theme', 't')
            ->leftJoin('a.support' , 's')
            ->andWhere('c.id = :idcollege')
            ->setParameter('idcollege', $idcollege)
            ->orderBy('a.updatedAt', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function listFiveArticles($category)
    {
        return $this->createQueryBuilder('a')
            ->addSelect('
                a.id as id, 
                a.title as title, 
                a.slug as slug, 
                a.content as content,
                a.createdAt As createdAt, 
                t.id as idtheme, 
                t.name as theme, 
                a.imageName, 
                a.isTitleShow, 
                a.isShowReadMore, 
                s.id as idsupport, 
                s.name as support')
            ->leftJoin('a.college', 'c')
            ->leftJoin('a.theme', 't')
            ->leftJoin('a.support' , 's')
            ->leftJoin('a.category', 'ca')
            ->andWhere('ca.id = :category')
            ->setParameter('category', $category)
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
    public function articlecollegeSlug($slug)
    {
        return $this->createQueryBuilder('a')
            ->addSelect('a.id as id, a.slug, a.title as title, a.content as content, a.doc, t.id as idtheme, t.name as theme, a.imageName, a.isTitleShow, a.intro, a.isShowIntro, c.name, c.id AS collegeId,c.headerName, c.logoName, c.GroupDescription, a.isShowReadMore, s.id as idsupport, s.name as support')
            ->leftJoin('a.college', 'c')
            ->leftJoin('a.theme', 't')
            ->leftJoin('a.support' , 's')
            ->leftJoin('a.category', 'ca')
            ->andWhere('a.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
