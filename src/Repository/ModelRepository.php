<?php

namespace App\Repository;

use App\Entity\Brand;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

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

    public function findAllByBrandUrl($brandUrl)
    {
        $qb = $this->createQueryBuilder('m')
            ->select('m');

        return $this->findByBrandUrl($qb, $brandUrl)
            ->getQuery()
            ->getResult();
    }

    public function findAllUrlByBrandUrl($brandUrl)
    {
        $qb = $this->createQueryBuilder('m')
            ->select('m.url');

        return $this->findByBrandUrl($qb, $brandUrl)
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

    public function findModelForImport($text, Brand $brand)
    {
        $qb = $this->createQueryBuilder('m')
            ->select('m')
            ->where("m.brand = :brand");

        $orX = $qb->expr()->orX(
            'REPLACE(REPLACE(m.name, \'"("\', \'""\'), \'")"\', \'""\') = :textUpper',
            'REPLACE(REPLACE(m.name, \'"("\', \'""\'), \'")"\', \'""\') LIKE :textLike',
            'm.name = :textUpper',
            'm.name LIKE :textLike',
            'm.modelEn = :text',
            'm.modelEn = :textWithoutYear'
        );

        return $qb->andWhere($orX)
            ->setParameter("brand",  $brand)
            ->setParameter('text', $text )
            ->setParameter('textLike', '%' . $text . '%'  )
            ->setParameter('textUpper', strtoupper($text) )
            ->setParameter('textWithoutYear', trim(preg_replace("/\d{4}\-\d{4}/", "", $text)) )
            ->getQuery()
            ->getResult();
    }

    private function findByBrandUrl(QueryBuilder $queryBuilder, $brandUrl)
    {
        return $queryBuilder->join("m.brand", "br")
            ->where("m.isPopular = :trueValue")
            ->andWhere("br.url = :brandUrl")
            ->setParameter("trueValue", true)
            ->setParameter("brandUrl", $brandUrl);
    }
}