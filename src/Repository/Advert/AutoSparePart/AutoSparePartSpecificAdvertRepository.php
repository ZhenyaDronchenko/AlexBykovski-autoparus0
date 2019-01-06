<?php

namespace App\Repository\Advert\AutoSparePart;

use App\Entity\Brand;
use App\Entity\City;
use App\Entity\Client\Client;
use App\Entity\Model;
use App\Entity\SparePart;
use App\Provider\SellerOffice\SpecificAdvertListProvider;
use App\Type\AutoSparePartSpecificAdvertFilterType;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;

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
}