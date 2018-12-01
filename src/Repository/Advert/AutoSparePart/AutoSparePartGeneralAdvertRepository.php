<?php

namespace App\Repository\Advert\AutoSparePart;

use App\Entity\Brand;
use App\Entity\Model;
use App\Entity\SparePart;
use Doctrine\ORM\EntityRepository;

class AutoSparePartGeneralAdvertRepository extends EntityRepository
{
    /**
     * @param string $text
     * @return array
     */
    public function findByParameters(SparePart $sparePart, Brand $brand, Model $model)
    {
        return $this->createQueryBuilder('adv')
            ->select('adv')
            ->join("adv.spareParts", "sp")
            ->join("adv.models", "model")
            ->where("sp = :sparePart")
            ->andWhere("adv.brand = :brand AND model = :model OR adv.brand IS NULL")
            ->setParameter("sparePart", $sparePart)
            ->setParameter("brand", $brand)
            ->setParameter("model", $model)
            ->getQuery()
            ->getResult();
    }
}