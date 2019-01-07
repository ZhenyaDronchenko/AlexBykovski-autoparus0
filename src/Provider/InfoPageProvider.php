<?php

namespace App\Provider;

use App\Entity\Brand;
use App\Entity\City;
use App\Entity\General\InfoPageBase;
use App\Entity\SparePart;
use Doctrine\ORM\EntityManagerInterface;

class InfoPageProvider
{
    const SPARE_PART_PAGE_NUMBER = [
        "general_to_users_page" => "getAlternativeName1",
        "general_to_seller_page" => "getAlternativeName2",
        "general_news_page" => "getAlternativeName3",
        "general_about_page" => "getAlternativeName4",
    ];

    const ORDER_CITY = ["Минск", "Брест", "Витебск", "Гродно", "Гомель", "Могилев"];

    /** @var EntityManagerInterface */
    private $em;

    /**
     * InfoPageProvider constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getCities(InfoPageBase $infoPage)
    {
        /** @var City $capital */
        $capital = $this->em->getRepository(City::class)->findOneBy(["type" => City::CAPITAL_TYPE]);
        /** @var City[] $regionalCities */
        $regionalCities = $this->em->getRepository(City::class)->findBy(["type" => City::REGIONAL_CITY_TYPE]);

        $capitalUrlMethod = InfoPageBase::CITY_LINKS[$capital->getUrl()];

        $cities = [
            [
                "name" => $capital->getName(),
                "logo" => $capital->getLogo(),
                "prepositional" => $capital->getPrepositional(),
                "url" => $infoPage->$capitalUrlMethod(),
            ]
        ];


        foreach ($regionalCities as $city){
            $cityUrlMethod = InfoPageBase::CITY_LINKS[$city->getUrl()];

            $cities[] = [
                "name" => $city->getName(),
                "logo" => $city->getLogo(),
                "prepositional" => $city->getPrepositional(),
                "url" => $infoPage->$cityUrlMethod(),
            ];
        }

        usort($cities, function($city1, $city2){
            return array_search($city1["name"], self::ORDER_CITY) > array_search($city2["name"], self::ORDER_CITY);
        });

        return $cities;
    }

    public function getBrands()
    {
        /** @var Brand[] $brands */
        $brands = $this->em->getRepository(Brand::class)->findAllActivePopularByAlphabetic();

        $parsedBrands = [];

        foreach ($brands as $brand){
            $parsedBrands[] = [
                "enBrand" => $brand->getBrandEn(),
                "url" => $brand->getUrl(),
                "logo" => $brand->getLogo(),
            ];
        }

        return $parsedBrands;
    }

    public function getSpareParts($route)
    {
        /** @var SparePart[] $spareParts */
        $spareParts = $this->em->getRepository(SparePart::class)->findAllActivePopularByAlphabetic();

        $parsedSpareParts = [];

        foreach ($spareParts as $sparePart){
            $nameMethod = self::SPARE_PART_PAGE_NUMBER[$route];

            $parsedSpareParts[] = [
                "name" => $sparePart->$nameMethod(),
                "url" => $sparePart->getUrl(),
                "logo" => $sparePart->getLogo(),
                "multiple" => $sparePart->getNamePlural(),
            ];
        }

        return $parsedSpareParts;
    }
}