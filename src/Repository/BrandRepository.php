<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class BrandRepository extends EntityRepository
{
    /**
     * @param string $text
     * @return array
     */
    public function searchByText($text)
    {
        return $this->createQueryBuilder('br')
            ->select('br')
            ->where("br.name LIKE :text")
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
}