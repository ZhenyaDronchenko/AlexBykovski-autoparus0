<?php

namespace App\Repository\Advert\AutoSparePart;

use App\Entity\Advert\AutoSparePart\AutoSparePartSpecificAdvert;
use App\Entity\Brand;
use App\Entity\City;
use App\Entity\Client\Client;
use App\Entity\Client\SellerAdvertDetail;
use App\Entity\SparePart;
use App\Provider\SellerOffice\SpecificAdvertListProvider;
use App\SiteMap\Provider\FreshProductPagesSiteMapUrlProvider;
use App\Type\AutoSparePartSpecificAdvertFilterType;
use App\Type\CatalogAdvertFilterType;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;

class AutoSparePartSpecificAdvertRepository extends EntityRepository
{
    /**
     * @param Client $client
     *
     * @return array
     */
    public function findAllIncludedBrandNames(Client $client)
    {
        return $this->createQueryBuilder('spAdv')
            ->select('br.id, br.name')
            ->join("spAdv.brand", "br")
            ->where("spAdv.sellerAdvertDetail = :advertDetail")
            ->setParameter("advertDetail", $client->getSellerData()->getAdvertDetail())
            ->distinct()
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Client $client
     *
     * @return array
     */
    public function findAllIncludedSparePartNames(Client $client)
    {
        return $this->createQueryBuilder('spAdv')
            ->select('spp.id, spp.name')
            ->join(SparePart::class, 'spp', Join::WITH, 'spp.name = spAdv.sparePart')
            ->where("spAdv.sellerAdvertDetail = :advertDetail")
            ->setParameter("advertDetail", $client->getSellerData()->getAdvertDetail())
            ->distinct()
            ->getQuery()
            ->getResult();
    }

    public function findAllIncludedModelNamesByBrand(Client $client, Brand $brand)
    {
        return $this->createQueryBuilder('spAdv')
            ->select('m.id, m.name')
            ->join("spAdv.model", "m")
            ->where("spAdv.sellerAdvertDetail = :advertDetail")
            ->andWhere("spAdv.brand = :brand")
            ->setParameter("advertDetail", $client->getSellerData()->getAdvertDetail())
            ->setParameter("brand", $brand)
            ->distinct()
            ->getQuery()
            ->getResult();
    }

    public function findAllByFilter(AutoSparePartSpecificAdvertFilterType $filterType)
    {
        $limit = SpecificAdvertListProvider::COUNT_ON_PAGE;
        $offset = max(SpecificAdvertListProvider::COUNT_ON_PAGE * ($filterType->getPage() - 1), 0);

        $qb = $this->getBaseFilterSearch($filterType);

        return $qb->select('spAdv')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->orderBy("spAdv.updatedAt", "DESC")
            ->getQuery()
            ->getResult();
    }

    public function findCountByFilter(AutoSparePartSpecificAdvertFilterType $filterType)
    {
        $qb = $this->getBaseFilterSearch($filterType);

        return $qb->select('COUNT(spAdv) as count')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findAllForCatalog(CatalogAdvertFilterType $filterType)
    {
        $qb = $this->createQueryBuilder('spAdv');
        $stockTypes = $filterType->getInStock() ?
            [AutoSparePartSpecificAdvert::IN_STOCK_TYPE] :
            [AutoSparePartSpecificAdvert::UNDER_ORDER_TYPE, AutoSparePartSpecificAdvert::IN_STOCK_TYPE];

        if($filterType->getBrand()){
            $qb->andWhere("spAdv.brand = :brand")
                ->setParameter("brand", $filterType->getBrand());
        }

        if($filterType->getModel()){
            $qb->andWhere("spAdv.model = :model")
                ->setParameter("model", $filterType->getModel());
        }

        if($filterType->getSparePart()){
            $qb->andWhere("spAdv.sparePart = :sparePart")
                ->setParameter("sparePart", $filterType->getSparePart()->getName());
        }

        if($filterType->getCity()){
            $qb->join("spAdv.sellerAdvertDetail", "sad")
                ->join("sad.sellerData", "sd")
                ->join("sd.sellerCompany", "sc")
                ->andWhere("sc.city = :city")
                ->setParameter("city", $filterType->getCity()->getName());
        }

        $qb->andWhere("spAdv.stockType IN(:stockTypes)")
            ->setParameter("stockTypes", $stockTypes);

        return $qb->getQuery()
            ->getResult();
    }

    protected function getBaseFilterSearch(AutoSparePartSpecificAdvertFilterType $filterType)
    {
        $qb = $this->createQueryBuilder('spAdv')
            ->where("spAdv.sellerAdvertDetail = :advertDetail")
            ->setParameter("advertDetail", $filterType->getClient()->getSellerData()->getAdvertDetail());

        if($filterType->getBrand()){
            $qb->andWhere("spAdv.brand = :brand")
                ->setParameter("brand", $filterType->getBrand());
        }

        if($filterType->getModel()){
            $qb->andWhere("spAdv.model = :model")
                ->setParameter("model", $filterType->getModel());
        }

        if($filterType->getSparePart()){
            $qb->andWhere("spAdv.sparePart = :sparePart")
                ->setParameter("sparePart", $filterType->getSparePart()->getName());
        }

        return $qb;
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

    /**
     * @param SellerAdvertDetail $advertDetail
     * @param int $count
     *
     * @return array
     */
    public function findMoreAdverts(SellerAdvertDetail $advertDetail, $count = 2)
    {
        return $this->createQueryBuilder('spAdv')
            ->select('DISTINCT spAdv.sparePart, spAdv')
            ->where("spAdv.sellerAdvertDetail = :advertDetail")
            ->setParameter("advertDetail", $advertDetail)
            ->setMaxResults($count)
            ->getQuery()
            ->getResult();
    }

    public function findFreshForSitemap($step)
    {
//        var_dump(FreshProductPagesSiteMapUrlProvider::COUNT);
//        var_dump((int)$step * FreshProductPagesSiteMapUrlProvider::COUNT);
//
//        die;
        return $this->createQueryBuilder('spAdv')
            ->select('spAdv.id as id, br.url as urlBrand, m.url as urlModel, sc.city as cityName, spAdv.sparePart as spName')
            ->join("spAdv.model", "m")
            ->join("spAdv.brand", "br")
            ->join("spAdv.sellerAdvertDetail", "sad")
            ->join("sad.sellerData", "sd")
            ->join("sd.sellerCompany", "sc")
//            ->innerJoin(SparePart::class, "sp", Join::WITH, "sp.name = spAdv.sparePart")
//            ->innerJoin(City::class, "city", Join::WITH, "city.name = sc.city")
            ->setMaxResults(FreshProductPagesSiteMapUrlProvider::COUNT)
            ->setFirstResult((int)$step * FreshProductPagesSiteMapUrlProvider::COUNT)
            ->orderBy("spAdv.id", "DESC")
            ->getQuery()
            ->getResult();
    }

    /**
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getAllCount()
    {
        return $this->createQueryBuilder('spAdv')
            ->select('COUNT(spAdv) as count')
            ->getQuery()
            ->getSingleScalarResult();
    }
}