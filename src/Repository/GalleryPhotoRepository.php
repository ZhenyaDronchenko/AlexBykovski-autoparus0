<?php

namespace App\Repository;

use App\Entity\Client\Client;
use App\Entity\Client\SellerCompany;
use App\Entity\User;
use App\Type\PostsFilterType;
use Doctrine\ORM\EntityRepository;

class GalleryPhotoRepository extends EntityRepository
{
    public function findAllByFilter(PostsFilterType $filter)
    {
        $qb = $this->createQueryBuilder('gph')
            ->select('gph')
            ->join("gph.image", "im")
            ->join("gph.gallery", "g")
            ->join("g.client", "cl");

        if($filter->getOffset() || $filter->getLimit() === 0){
            $qb->setFirstResult( $filter->getOffset() );
        }

        if($filter->getLimit() || $filter->getLimit() === 0){
            $qb->setMaxResults( $filter->getLimit() );
        }

        if($filter->getUsers() instanceof Client){
            $qb->andWhere("cl.id = :clientId")
                ->setParameter("clientId", $filter->getUsers()->getId());
        }
        elseif($filter->getUsers() === PostsFilterType::USERS_ACCESS_POST_HOMEPAGE){
            $qb->where('cl.roles LIKE :role')
                ->setParameter('role', '%' . User::ROLE_SHOW_POSTS_HOMEPAGE . '%');
        }

        if($filter->getBrand() || $filter->getModel()){
            $qb->join("gph.cars", "car");

            if($filter->getBrand()){
                $qb->andWhere("car.brand = :brand")
                    ->setParameter("brand", $filter->getBrand()->getName());
            }

            if($filter->getModel()){
                $qb->andWhere("car.model = :model")
                    ->setParameter("model", $filter->getModel()->getName());
            }
        }

        if($filter->getCity() || $filter->getActivity()){
            $qb->join("gph.businessActivities", "ba");

            if($filter->getCity()){
                $qb->andWhere("ba.city = :city")
                    ->setParameter("city", $filter->getCity()->getName());
            }

            if($filter->getActivity()){
                $qb->andWhere("ba.activity = :activity")
                    ->setParameter("activity", array_flip(SellerCompany::$activities)[$filter->getActivity()]);
            }
        }

        return $qb->orderBy("im.createdAt", "DESC")
            ->getQuery()
            ->getResult();
    }
}