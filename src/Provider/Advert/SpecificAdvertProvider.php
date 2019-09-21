<?php

namespace App\Provider\Advert;

use App\Entity\Advert\AutoSparePart\AutoSparePartSpecificAdvert;
use App\Entity\Advert\CurrencyRate;
use App\Type\CatalogAdvertFilterType;
use Doctrine\ORM\EntityManagerInterface;

class SpecificAdvertProvider
{
    /** @var EntityManagerInterface */
    private $em;

    private $currencies;

    /**
     * SpecificAdvertProvider constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function provideSortedListAdverts(CatalogAdvertFilterType $catalogFilter)
    {
        $specificAdverts = $this->em->getRepository(AutoSparePartSpecificAdvert::class)
            ->findAllForCatalog($catalogFilter);
        $sortedAdverts = [];

        /** @var AutoSparePartSpecificAdvert $advert */
        foreach ($specificAdverts as $advert){
            $cost = $advert->getCost() * $this->getCurrencyRate($advert->getCurrency());

            $sortedAdverts[$cost . '_' . $advert->getId()] = $advert;
        }

        uksort($sortedAdverts, function ($key1, $key2){
            if(strpos($key1, "0_") === 0){
                return 1;
            }
            if(strpos($key2, "0_") === 0){
                return -1;
            }

            return $key1 > $key2 ? 1 : -1;
        });

        return $sortedAdverts;
    }

    private function getCurrencyRate($code)
    {
        if(!$code){
            return 1; // means BYN
        }
        if(array_key_exists($code, $this->currencies)){
            return $this->currencies[$code];
        }

        $currency = $this->em->getRepository(CurrencyRate::class)->findOneBy(["code" => $code]);

        if(!($currency instanceof CurrencyRate)){
            $this->currencies[$code] = 0;
        }

        return $this->currencies[$code];
    }
}