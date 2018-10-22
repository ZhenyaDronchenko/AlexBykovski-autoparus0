<?php

namespace App\Repository;

use App\Entity\Phone\PhoneBrand;
use Doctrine\ORM\EntityRepository;

class PhoneModelRepository extends EntityRepository
{
    /**
     * @param string $text
     * @param PhoneBrand $brand
     *
     * @return array
     */
    public function searchByText($text, PhoneBrand $brand = null)
    {
        $query = $this->createQueryBuilder('phm')
            ->select('phm')
            ->where("phm.name LIKE :text")
            ->andWhere("phm.active = :trueValue")
            ->setParameter("trueValue", true)
            ->setParameter("text", '%' . $text . '%');

        if($brand){
            $query = $query
                ->andWhere("phm.brand = :brand")
                ->setParameter("brand", $brand);
        }

        return $query->orderBy("phm.name", "ASC")
            ->getQuery()
            ->getResult();
    }
}