<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class SparePartRepository extends EntityRepository
{
    /**
     * @param string $text
     * @return array
     */
    public function searchByText($text)
    {
        return $this->createQueryBuilder('spp')
            ->select('spp')
            ->where("spp.name LIKE :text")
            ->andWhere("spp.active = :trueValue")
            ->setParameter("trueValue", true)
            ->setParameter("text", '%' . $text . '%')
            ->orderBy("spp.name", "ASC")
            ->getQuery()
            ->getResult();
    }

    public function findAllForAdvert()
    {
        return $this->createQueryBuilder('spp')
            ->select('spp.id, spp.name')
            ->orderBy("spp.name", "ASC")
            ->getQuery()
            ->getResult();
    }

    public function findAllUrlsForSiteMap()
    {
        return $this->createQueryBuilder('spp')
            ->select('spp.url')
            ->where("spp.active = :trueValue")
            ->setParameter("trueValue", true)
            ->getQuery()
            ->getResult();
    }

    public function findPopularUrlsForSiteMap()
    {
        return $this->createQueryBuilder('spp')
            ->select('spp.url')
            ->where("spp.active = :trueValue")
            ->where("spp.popular = :trueValue")
            ->setParameter("trueValue", true)
            ->getQuery()
            ->getResult();
    }

    public function findAllOnlyField($field, $isSort = false)
    {
        $qb = $this->createQueryBuilder('spp')
            ->select('spp.id, spp.' . $field);

        if($isSort){
            $qb->orderBy("spp." . $field, "ASC");
        }

        return $qb->getQuery()
            ->getResult();
    }

    /**
     * @return array
     */
    public function findAllActivePopularByAlphabetic()
    {
        return $this->createQueryBuilder('spp')
            ->select('spp')
            ->where("spp.popular = :trueValue")
            ->andWhere("spp.active = :trueValue")
            ->setParameter("trueValue", true)
            ->orderBy("spp.name", "ASC")
            ->getQuery()
            ->getResult();
    }

    public function findSparePartForImport($text)
    {
        $qb = $this->createQueryBuilder('spp')
            ->select('spp');

        $orX = $qb->expr()->orX(
            "UPPER(spp.keyWords) = UPPER(:text)",
            "UPPER(spp.keyWords) LIKE CONCAT('%|', UPPER(:text))",
            "UPPER(spp.keyWords) LIKE CONCAT(UPPER(:text), '|%')",
            "UPPER(spp.keyWords) LIKE CONCAT('%|', UPPER(:text), '|%')"
        );

        return $qb->where($orX)
            ->setParameter('text', $text)
            ->getQuery()
            ->getResult();
    }

    public function findSparePartsForAutoSet()
    {
        return  $this->createQueryBuilder('spp')
            ->select('spp.id, spp.name')
            ->where("spp.active = :trueValue")
            ->setParameter("trueValue", true)
            ->orderBy("spp.name", "ASC")
            ->getQuery()
            ->getResult();
    }
}