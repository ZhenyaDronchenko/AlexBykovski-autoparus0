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

        $texts = [trim($text)];

        $texts[] = trim(strtok($text, '('));
        $texts[] = trim(strtok($text, '/'));

        $orX = $qb->expr()->orX(
            'spp.name IN (:texts)',
            'TRIM(SUBSTRING_INDEX(spp.name, \'(\', 1 )) IN (:texts)',
            'TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(spp.name,\')\',1),\'(\',-1)) IN (:texts)',
            'spp.alternativeName1 IN (:texts)',
            'TRIM(SUBSTRING_INDEX(spp.alternativeName1, \'(\', 1 )) IN (:texts)',
            'TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(spp.alternativeName1,\')\',1),\'(\',-1)) IN (:texts)',
            'spp.alternativeName2 IN (:texts)',
            'TRIM(SUBSTRING_INDEX(spp.alternativeName2, \'(\', 1 )) IN (:texts)',
            'TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(spp.alternativeName2,\')\',1),\'(\',-1)) IN (:texts)',
            'spp.alternativeName3 IN (:texts)',
            'TRIM(SUBSTRING_INDEX(spp.alternativeName3, \'(\', 1 )) IN (:texts)',
            'TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(spp.alternativeName3,\')\',1),\'(\',-1)) IN (:texts)',
            'spp.alternativeName4 IN (:texts)',
            'TRIM(SUBSTRING_INDEX(spp.alternativeName4, \'(\', 1 )) IN (:texts)',
            'TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(spp.alternativeName4,\')\',1),\'(\',-1)) IN (:texts)'
        );

        $qb->where($orX)
            ->setParameter('texts', $texts );

        return $qb
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

    public function findForSpeech($text)
    {
        $qb = $this->createQueryBuilder('spp')
            ->select('spp');

        $orX = $qb->expr()->orX(
            ':text LIKE CONCAT(\'%\', UPPER(spp.name), \'%\')',
            ':text LIKE CONCAT(\'%\', UPPER(SUBSTRING_INDEX(spp.name, \'(\', 1 )), \'%\')',
            ':text LIKE CONCAT(\'%\', UPPER(SUBSTRING_INDEX(SUBSTRING_INDEX(spp.name,\')\',1),\'(\',-1)), \'%\')',
//            ':text LIKE CONCAT(\'%\', UPPER(spp.alternativeName1), \'%\')',
//            ':text LIKE CONCAT(\'%\', UPPER(SUBSTRING_INDEX(spp.alternativeName1, \'(\', 1 )), \'%\')',
//            ':text LIKE CONCAT(\'%\', UPPER(SUBSTRING_INDEX(SUBSTRING_INDEX(spp.alternativeName1,\')\',1),\'(\',-1)), \'%\')',
//            ':text LIKE CONCAT(\'%\', UPPER(spp.alternativeName2), \'%\')',
//            ':text LIKE CONCAT(\'%\', UPPER(SUBSTRING_INDEX(spp.alternativeName2, \'(\', 1 )), \'%\')',
//            ':text LIKE CONCAT(\'%\', UPPER(SUBSTRING_INDEX(SUBSTRING_INDEX(spp.alternativeName2,\')\',1),\'(\',-1)), \'%\')',
//            ':text LIKE CONCAT(\'%\', UPPER(spp.alternativeName3), \'%\')',
//            ':text LIKE CONCAT(\'%\', UPPER(SUBSTRING_INDEX(spp.alternativeName3, \'(\', 1 )), \'%\')',
//            ':text LIKE CONCAT(\'%\', UPPER(SUBSTRING_INDEX(SUBSTRING_INDEX(spp.alternativeName3,\')\',1),\'(\',-1)), \'%\')',
//            ':text LIKE CONCAT(\'%\', UPPER(spp.alternativeName4), \'%\')',
//            ':text LIKE CONCAT(\'%\', UPPER(SUBSTRING_INDEX(spp.alternativeName4, \'(\', 1 )), \'%\')',
//            ':text LIKE CONCAT(\'%\', UPPER(SUBSTRING_INDEX(SUBSTRING_INDEX(spp.alternativeName4,\')\',1),\'(\',-1)), \'%\')',
            ':text = UPPER(spp.name)',
            ':text = UPPER(SUBSTRING_INDEX(spp.name, \'(\', 1 ))',
            ':text = UPPER(SUBSTRING_INDEX(SUBSTRING_INDEX(spp.name,\')\',1),\'(\',-1))'
//            ':text = UPPER(spp.alternativeName1)',
//            ':text = UPPER(SUBSTRING_INDEX(spp.alternativeName1, \'(\', 1 ))',
//            ':text = UPPER(SUBSTRING_INDEX(SUBSTRING_INDEX(spp.alternativeName1,\')\',1),\'(\',-1))',
//            ':text = UPPER(spp.alternativeName2)',
//            ':text = UPPER(SUBSTRING_INDEX(spp.alternativeName2, \'(\', 1 ))',
//            ':text = UPPER(SUBSTRING_INDEX(SUBSTRING_INDEX(spp.alternativeName2,\')\',1),\'(\',-1))',
//            ':text = UPPER(spp.alternativeName3)',
//            ':text = UPPER(SUBSTRING_INDEX(spp.alternativeName3, \'(\', 1 ))',
//            ':text = UPPER(SUBSTRING_INDEX(SUBSTRING_INDEX(spp.alternativeName3,\')\',1),\'(\',-1))',
//            ':text = UPPER(spp.alternativeName4)',
//            ':text = UPPER(SUBSTRING_INDEX(spp.alternativeName4, \'(\', 1 ))',
//            ':text = UPPER(SUBSTRING_INDEX(SUBSTRING_INDEX(spp.alternativeName4,\')\',1),\'(\',-1))'
        );

        $qb->where($orX)
            ->setParameter('text', strtoupper($text) );

        var_dump($qb->getQuery()->getDQL());
        return $qb
            ->getQuery()
            ->getResult();
    }
}