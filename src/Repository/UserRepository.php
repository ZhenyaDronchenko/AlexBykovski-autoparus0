<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    const POSSIBLE_PHONE_SYMBOLS = ['+', '(', ')', '-', ' '];

    /**
     * @param $phone
     * @return User|null
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByOnlyNumbersPhone($phone)
    {
        return $this->createQueryBuilder('u')
            ->select('u')
            ->where($this->replaceAllPhoneSymbols("u.phone") . " = :phone")
            ->setParameter("phone", preg_replace('/[^0-9]/', '', $phone))
            ->getQuery()
            ->getOneOrNullResult();
    }

    private function replaceAllPhoneSymbols($subject)
    {
        $query = $subject;

        foreach (self::POSSIBLE_PHONE_SYMBOLS as $symbol){
            $query = "REPLACE(" . $query . ", '" . $symbol ."', '')";
        }

        return $query;
    }
}