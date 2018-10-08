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
    public function searchByText($text, Brand $brand = null)
    {
        $query = $this->createQueryBuilder('m')
            ->select('m')
            ->where("m.name LIKE :text")
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
            ->select('m.name')
            ->where("m.brand = :brand")
            ->setParameter("brand", $brand);

        if($isSort){
            $qb->orderBy("br.name", "ASC");
        }


        return $qb->getQuery()
            ->getResult();
    }
}