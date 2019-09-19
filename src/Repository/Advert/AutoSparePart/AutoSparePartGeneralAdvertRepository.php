<?php

namespace App\Repository\Advert\AutoSparePart;

use App\Entity\Advert\AutoSparePart\AutoSparePartGeneralAdvert;
use App\Entity\Advert\AutoSparePart\AutoSparePartSpecificAdvert;
use App\Entity\Brand;
use App\Entity\City;
use App\Entity\Client\SellerAdvertDetail;
use App\Entity\Model;
use App\Entity\SparePart;
use App\Type\CatalogAdvertFilterType;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class AutoSparePartGeneralAdvertRepository extends EntityRepository
{
    /**
     * @param SparePart $sparePart
     * @param Brand $brand
     * @param Model $model
     * @param City|null $city
     * @param string|null $stockTypes
     * @param int|null $limit
     * @param bool|null $byBrand
     *
     * @return array
     */
    public function findByParameters(
        SparePart $sparePart,
        Brand $brand,
        Model $model = null,
        City $city = null,
        $stockTypes = null,
        $limit = null,
        $byBrand = null
    )
    {
        $q = $this->createQueryBuilder('adv')
            ->select('adv')
            ->join("adv.spareParts", "sp")
            ->where("sp = :sparePart")
            ->setParameter("sparePart", $sparePart);

        if($stockTypes){
            $q->andWhere("adv.stockType IN(:stockTypes)")
                ->setParameter("stockTypes", $stockTypes);
        }

        if($model instanceof Model){
            $this->getSearchByBrandWithModel($q, $byBrand, $brand, $model);
        }
        else{
            $this->getSearchByBrand($q, $byBrand, $brand);
        }

        if($city instanceof City){
            $q->join("adv.sellerAdvertDetail", "sad")
                ->join("sad.sellerData", "sd")
                ->join("sd.sellerCompany", "sc")
                ->andWhere("sc.city = :city")
                ->setParameter("city", $city->getName());
        }

        if($limit){
            $q->setMaxResults($limit);
        }

        return $q->orderBy("adv.updatedAt", "DESC")
            ->getQuery()
            ->getResult();
    }

    /**
     * @param SellerAdvertDetail $advertDetail
     *
     * @return int
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countForUser(SellerAdvertDetail $advertDetail)
    {
        return $this->createQueryBuilder('adv')
            ->select('count(adv)')
            ->where("adv.sellerAdvertDetail = :advertDetail")
            ->setParameter("advertDetail", $advertDetail)
            ->getQuery()
            ->getSingleScalarResult();
    }

    //$byBrand - null: brand = brand && brand = null
    //           true: brand = brand
    //           false: brand = null
    public function findAllForCatalog(CatalogAdvertFilterType $filterType, $byBrand = null)
    {
        $sparePart = $filterType->getSparePart();
        $brand = $filterType->getBrand();
        $model = $filterType->getModel();
        $city = $filterType->getCity();
        $limit = $filterType->getLimit();
        $stockTypes = $filterType->getInStock() ?
            [AutoSparePartSpecificAdvert::IN_STOCK_TYPE] :
            [AutoSparePartSpecificAdvert::UNDER_ORDER_TYPE, AutoSparePartSpecificAdvert::IN_STOCK_TYPE,
                AutoSparePartGeneralAdvert::STOCK_TYPE_CHECK_AVAILABILITY];

        return $this->findByParameters($sparePart, $brand, $model, $city, $stockTypes, $limit, $byBrand);
    }

    private function getSearchByBrandWithModel(QueryBuilder $q, $byBrand, $brand, $model)
    {
        switch ($byBrand){
            case true:
                $q->leftJoin("adv.models", "model")
                    ->andWhere("adv.brand = :brand AND model = :model")
                    ->setParameter("model", $model)
                    ->setParameter("brand", $brand);

                break;
            case false:
                $q->andWhere("adv.brand IS NULL");

                break;
            default:
                $q->leftJoin("adv.models", "model")
                    ->andWhere("adv.brand = :brand AND model = :model OR adv.brand IS NULL")
                    ->setParameter("model", $model)
                    ->setParameter("brand", $brand);

                break;
        }
    }

    private function getSearchByBrand(QueryBuilder $q, $byBrand, $brand)
    {
        switch ($byBrand){
            case true:
                $q->andWhere("adv.brand = :brand")
                    ->setParameter("brand", $brand);

                break;
            case false:
                $q->andWhere("adv.brand IS NULL");

                break;
            default:
                $q->andWhere("adv.brand = :brand OR adv.brand IS NULL")
                    ->setParameter("brand", $brand);

                break;
        }
    }
}