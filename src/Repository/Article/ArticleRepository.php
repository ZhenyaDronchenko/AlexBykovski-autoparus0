<?php

namespace App\Repository\Article;

use App\Entity\Client\Client;
use App\Entity\Client\SellerCompany;
use App\Entity\User;
use App\Type\ArticleFilterType;
use App\Type\PostsFilterType;
use DateTime;
use Doctrine\ORM\EntityRepository;

class ArticleRepository extends EntityRepository
{
    public function findAllOnlyId($isSort = false)
    {
        $qb = $this->createQueryBuilder('art')
            ->select('art.id');

        if($isSort){
            $qb->orderBy("art.id", "DESC");
        }

        return $qb->getQuery()
            ->getResult();
    }

    public function findAllByFilter(ArticleFilterType $filter, $notIds = [])
    {
        $notIds = [];

        $qb = $this->createQueryBuilder('a')
            ->select('a as object, (a.views + a.directViews) as all_views')
            ->join("a.detail", "d")
            ->where("a.isActive = :trueValue")
            ->setParameter("trueValue", true);

        if(count($notIds)){
          $qb->andWhere("a.id NOT IN (:ids)")
                ->setParameter("ids", $notIds);
        }

        if($filter->getOffset()){
            $qb->setFirstResult( $filter->getOffset() );
        }

        if($filter->getLimit()){
            $qb->setMaxResults( $filter->getLimit() );
        }

        if(count($filter->getThemes())){
            $qb->join("d.themes", "theme")
                ->andWhere("theme IN (:themes)")
                ->setParameter("themes", $filter->getThemes());
        }

        if(count($filter->getNotThemes())){
            $notIds = array_merge($notIds, $this->findAllWithThemesIds($filter->getNotThemes()));
        }

        if(count($filter->getNotTypes())){
            $notIds = array_merge($notIds, $this->findAllWithTypesIds($filter->getNotTypes()));
        }

        if(count($notIds)){
            $qb->andWhere("a.id NOT IN(:notIds)")
                ->setParameter("notIds", $notIds);
        }

        if(count($filter->getTypes())){
            $qb->join("d.types", "type")
                ->andWhere("type IN (:types)")
                ->setParameter("types", $filter->getTypes());
        }

        switch ($filter->getTypeSort()){
            case ArticleFilterType::SORT_UPDATED:
                $qb->orderBy("a.updatedAt", "DESC");

                break;
            case ArticleFilterType::SORT_CREATED:
                $qb->orderBy("a.createdAt", "DESC");

                break;
            case ArticleFilterType::SORT_VIEWS:
                $qb->orderBy("all_views", "DESC");

                break;
        }

        return $qb->getQuery()
            ->getResult();
    }

    public function findAllForSitemap()
    {
        $todayMinus2Days = new DateTime("-2 days");

        return $this->createQueryBuilder('a')
            ->select('a')
            ->where("a.createdAt >= :firstDate")
            ->setParameter("firstDate", $todayMinus2Days)
            ->getQuery()
            ->getResult();
    }

    private function findAllWithThemesIds($themes)
    {
        $res = $this->createQueryBuilder('a')
            ->select('a.id')
            ->join("a.detail", "d")
            ->join("d.themes", "theme")
            ->andWhere("theme IN (:themes)")
            ->setParameter("themes", $themes)
            ->getQuery()
            ->getResult();

        $ids = [];

        foreach ($res as $item){
            $ids[] =  $item["id"];
        }

        return $ids;
    }

    private function findAllWithTypesIds($types)
    {
        $res = $this->createQueryBuilder('a')
            ->select('a.id')
            ->join("a.detail", "d")
            ->join("d.types", "type")
            ->andWhere("type IN (:types)")
            ->setParameter("types", $types)
            ->getQuery()
            ->getResult();

        $ids = [];

        foreach ($res as $item){
            $ids[] =  $item["id"];
        }

        return $ids;
    }
}