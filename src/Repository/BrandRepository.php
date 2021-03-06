<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class BrandRepository extends EntityRepository
{
    /**
     * @param string $text
     * @return array
     */
    public function searchByText($text, $isRussianText = false)
    {
        $searchField = $isRussianText ? "br.brandRu" : "br.name";

        return $this->createQueryBuilder('br')
            ->select('br')
            ->where($searchField . " LIKE :text")
            ->andWhere("br.active = :trueValue")
            ->setParameter("trueValue", true)
            ->setParameter("text", '%' . $text . '%')
            ->orderBy("br.name", "ASC")
            ->getQuery()
            ->getResult();
    }

    public function findAllOnlyField($field, $isSort = false)
    {
        $qb = $this->createQueryBuilder('br')
            ->select('br.id, br.' . $field);

        if($isSort){
            $qb->orderBy("br." . $field, "ASC");
        }

        return $qb->getQuery()
            ->getResult();
    }

    public function findAllForAdvert($exceptBrandIds)
    {
        $qb = $this->createQueryBuilder('br');

        return $qb
            ->select('br.id, br.brandEn')
            ->where($qb->expr()->notIn('br.id', $exceptBrandIds))
            ->orderBy("br.brandEn", "ASC")
            ->getQuery()
            ->getResult();
    }

    public function findAllUrlsForSiteMap($onlyActive = true)
    {
        $query = $this->createQueryBuilder('br')
            ->select('br.url');

        if($onlyActive) {
            $query->where("br.active = :trueValue")
                ->setParameter("trueValue", true);
        }

        return $query->getQuery()
            ->getResult();
    }

    public function findPopularUrlsForSiteMap()
    {
        return $this->createQueryBuilder('br')
            ->select('br.url, br.brandEn')
            ->where("br.popular = :trueValue")
            ->setParameter("trueValue", true)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array
     */
    public function findAllActivePopularByAlphabetic()
    {
        return $this->createQueryBuilder('br')
            ->select('br')
            ->where("br.popular = :trueValue")
            ->andWhere("br.active = :trueValue")
            ->setParameter("trueValue", true)
            ->orderBy("br.name", "ASC")
            ->getQuery()
            ->getResult();
    }

    public function findBrandForImport($text)
    {
        $qb = $this->createQueryBuilder('br')
            ->select('br');

        $orX = $qb->expr()->orX(
            "UPPER(br.keyWords) = UPPER(:text)",
            "UPPER(br.keyWords) LIKE CONCAT('%|', UPPER(:text))",
            "UPPER(br.keyWords) LIKE CONCAT(UPPER(:text), '|%')",
            "UPPER(br.keyWords) LIKE CONCAT('%|', UPPER(:text), '|%')"
        );

        return $qb->where($orX)
            ->setParameter('text', $text)
            ->getQuery()
            ->getResult();
    }
}