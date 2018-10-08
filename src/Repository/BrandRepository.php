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
            $qb->orderBy("br.name", "ASC");
        }

        return $qb->getQuery()
            ->getResult();
    }
}