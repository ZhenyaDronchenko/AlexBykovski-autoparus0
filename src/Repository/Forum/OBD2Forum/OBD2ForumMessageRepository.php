<?php

namespace App\Repository\Forum\OBD2Forum;

use App\Entity\Advert\AutoSparePart\AutoSparePartGeneralAdvert;
use App\Entity\Advert\AutoSparePart\AutoSparePartSpecificAdvert;
use App\Entity\Brand;
use App\Entity\City;
use App\Entity\Error\CodeOBD2Error;
use App\Entity\Error\TypeOBD2Error;
use App\Entity\Model;
use App\Entity\SparePart;
use App\Type\CatalogAdvertFilterType;
use Doctrine\ORM\EntityRepository;

class OBD2ForumMessageRepository extends EntityRepository
{
    /**
     * @param TypeOBD2Error $type
     * @param Brand $brand
     * @param Model|null $model
     * @param CodeOBD2Error $code
     *
     * @return array
     */
    public function findByParameters(Brand $brand, TypeOBD2Error $type, CodeOBD2Error $code, Model $model = null)
    {
        $q = $this->createQueryBuilder('obd2fm')
            ->select('obd2fm')
            ->join("obd2fm.technicalData", "td")
            ->where("td.brand = :brand")
            ->andWhere("td.type = :type")
            ->andWhere("td.code = :code")
            ->setParameter("brand", $brand)
            ->setParameter("type", $type)
            ->setParameter("code", $code);

        if($model){
            $q->andWhere("td.model = :model")
                ->setParameter("model", $model);
        }

        return $q->getQuery()
            ->getResult();
    }
}