<?php

namespace App\Repository\Advert\AutoSparePart;

use App\Entity\Brand;
use App\Entity\City;
use App\Entity\Model;
use App\Entity\SparePart;
use Doctrine\ORM\EntityRepository;

class AutoSparePartGeneralAdvertRepository extends EntityRepository
{
    /**
     * @param SparePart $sparePart
     * @param Brand $brand
     * @param Model $model
     * @param City|null $city
     * @param string|null $stockTypes
     *
     * @return array
     */
    public function findByParameters(SparePart $sparePart, Brand $brand, Model $model = null , City $city = null, $stockTypes = null)
    {
        $q = $this->createQueryBuilder('adv')
            ->select('adv')
            ->join("adv.spareParts", "sp")
            ->where("sp = :sparePart")
            ->setParameter("sparePart", $sparePart)
            ->setParameter("brand", $brand);

        if($stockTypes){
            $q->andWhere("adv.stockType IN(:stockTypes)")
                ->setParameter("stockTypes", $stockTypes);
        }

        if($model instanceof Model){
            $q->leftJoin("adv.models", "model")
                ->andWhere("adv.brand = :brand AND model = :model OR adv.brand IS NULL")
                ->setParameter("model", $model);
        }
        else{
            $q->andWhere("adv.brand = :brand OR adv.brand IS NULL");
        }

        if($city instanceof City){
            $q->join("adv.sellerAdvertDetail", "sad")
                ->join("sad.sellerData", "sd")
                ->join("sd.sellerCompany", "sc")
                ->andWhere("sc.city = :city")
                ->setParameter("city", $city->getName());
        }

        return $q->getQuery()
            ->getResult();
    }
}