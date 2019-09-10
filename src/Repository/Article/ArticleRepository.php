<?php

namespace App\Repository\Article;

use App\Entity\Client\Client;
use App\Entity\Client\SellerCompany;
use App\Entity\User;
use App\Type\ArticleFilterType;
use App\Type\PostsFilterType;
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
            if(!count($filter->getThemes())){
                $qb->join("d.themes", "theme");
            }
            $qb
                ->andWhere("theme NOT EXISTS (:notThemes)")
                ->setParameter("notThemes", $filter->getNotThemes());
        }

        if(is_bool($filter->getisOur())){
            $qb->andWhere("a.isOur = :isOur")
                ->setParameter("isOur", $filter->getisOur());
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
}