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
            'spp.name = :text',
            'spp.name LIKE :textLike',
            'spp.alternativeName1 = :text',
            'spp.alternativeName1 LIKE :textLike',
            'spp.alternativeName2 = :text',
            'spp.alternativeName2 LIKE :textLike',
            'spp.alternativeName3 = :text',
            'spp.alternativeName3 LIKE :textLike',
            'spp.alternativeName4 = :text',
            'spp.alternativeName4 LIKE :textLike',
            'spp.alternativeName5 = :text',
            'spp.alternativeName5 LIKE :textLike'
        );

        $qb->where($orX)
            ->setParameter('text', $text )
            ->setParameter('textLike', '%' . $text . '%' );

        preg_match_all("/\((.*?)\)/", $text, $matches);

        foreach ($matches[1] as $key => $match){
            $orX = $qb->expr()->orX(
                'spp.name = :text_' . $key,
                'spp.name LIKE :textLike_' . $key,
                'spp.alternativeName1 = :text_' . $key,
                'spp.alternativeName1 LIKE :textLike_' . $key,
                'spp.alternativeName2 = :text_' . $key,
                'spp.alternativeName2 LIKE :textLike_' . $key,
                'spp.alternativeName3 = :text_' . $key,
                'spp.alternativeName3 LIKE :textLike_' . $key,
                'spp.alternativeName4 = :text_' . $key,
                'spp.alternativeName4 LIKE :textLike_' . $key,
                'spp.alternativeName5 = :text_' . $key,
                'spp.alternativeName5 LIKE :textLike_' . $key
            );

            $qb->orWhere($orX)
                ->setParameter('text_' . $key, $match )
                ->setParameter('textLike_' . $key, '%' . $match . '%' );
        }



        return $qb
            ->getQuery()
            ->getResult();
    }
}