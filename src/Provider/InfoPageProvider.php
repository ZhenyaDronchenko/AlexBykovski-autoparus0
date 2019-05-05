<?php

namespace App\Provider;

use App\Entity\Brand;
use App\Entity\City;
use App\Entity\General\InfoPageBase;
use App\Entity\SparePart;
use App\Transformer\VariableTransformer;
use Doctrine\ORM\EntityManagerInterface;

class InfoPageProvider
{
    const SPARE_PART_PAGE_NUMBER = [
        "general_to_users_page" => "getAlternativeName1",
        "general_to_seller_page" => "getAlternativeName2",
        "general_news_page" => "getAlternativeName3",
        "general_about_page" => "getAlternativeName4",
    ];

    /** @var EntityManagerInterface */
    private $em;

    /** @var VariableTransformer */
    private $transformer;

    /**
     * InfoPageProvider constructor.
     *
     * @param EntityManagerInterface $em
     * @param VariableTransformer $transformer
     */
    public function __construct(EntityManagerInterface $em, VariableTransformer $transformer)
    {
        $this->em = $em;
        $this->transformer = $transformer;
    }

    public function getCities(InfoPageBase $infoPage)
    {
        /** @var City $capital */
        $capital = $this->em->getRepository(City::class)->findOneBy(["type" => City::CAPITAL_TYPE]);
        /** @var City[] $regionalCities */
        $regionalCities = $this->em->getRepository(City::class)->findBy(
            ["type" => City::REGIONAL_CITY_TYPE],
            ["name" => "ASC"]
        );

        $cities = [
            [
                "name" => $capital->getName(),
                "logo" => $capital->getLogo(),
                "prepositional" => $capital->getPrepositional(),
                "url" => $this->transformer->transformPage($infoPage->getCityLink(), [$capital]),
                "title" => $this->transformer->transformPage($infoPage->getCityTitle(), [$capital]),
            ]
        ];


        foreach ($regionalCities as $city){
            $cities[] = [
                "name" => $city->getName(),
                "logo" => $city->getLogo(),
                "prepositional" => $city->getPrepositional(),
                "url" => $this->transformer->transformPage($infoPage->getCityLink(), [$city]),
                "title" => $this->transformer->transformPage($infoPage->getCityTitle(), [$city]),
            ];
        }

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
                "name" => $brand->getName(),
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