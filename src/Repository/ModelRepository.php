<?php

namespace App\Repository;

use App\Entity\Brand;
use Doctrine\ORM\EntityRepository;

class ModelRepository extends EntityRepository
{
    /**
     * @param string $text
     * @param Brand $brand
     *
     * @return array
     */
    public function searchByText($text, Brand $brand = null, $isRussianText = false)
    {
        $searchField = $isRussianText ? "m.modelRu" : "m.name";

        $query = $this->createQueryBuilder('m')
            ->select('m')
            ->where($searchField . " LIKE :text")
            ->andWhere("m.active = :trueValue")
            ->setParameter("trueValue", true)
            ->setParameter("text", '%' . $text . '%');

        if($brand){
            $query = $query
                ->andWhere("m.brand = :brand")
                ->setParameter("brand", $brand);
        }

        return $query->orderBy("m.name", "ASC")
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Brand $brand
     * @param boolean $isSort
     *
     * @return array
     */
    public function findModelNamesByBrand(Brand $brand, $isSort = false)
    {
        $qb = $this->createQueryBuilder('m')
            ->select('m.id, m.name')
            ->where("m.brand = :brand")
            ->setParameter("brand", $brand);

        if($isSort){
            $qb->orderBy("m.name", "ASC");
        }


        return $qb->getQuery()
            ->getResult();
    }

    /**
     * @return array
     */
    public function findAllModelNames()
    {
        return $this->createQueryBuilder('m')
            ->select('m.id, m.name')
            ->getQuery()
            ->getResult();
    }

    public function findAllUrlByBrandUrl($brandUrl)
    {
        return $this->createQueryBuilder('m')
            ->select('m.url')
            ->join("m.brand", "br")
            ->where("m.active = :trueValue")
            ->andWhere("br.url = :brandUrl")
            ->setParameter("trueValue", true)
            ->setParameter("brandUrl", $brandUrl)
            ->getQuery()
            ->getResult();
    }

    public function findPopularUrlByBrandUrl($brandUrl)
    {
        return $this->createQueryBuilder('m')
            ->select('m.url')
            ->join("m.brand", "br")
            ->where("m.isPopular = :trueValue")
            ->andWhere("br.url = :brandUrl")
            ->setParameter("trueValue", true)
            ->setParameter("brandUrl", $brandUrl)
            ->getQuery()
            ->getResult();
    }
}