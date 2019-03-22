<?php

namespace App\Provider;

use App\Entity\Brand;
use App\Entity\City;
use App\Entity\General\InfoPageBase;
use App\Entity\General\MainPage;
use App\Entity\Model;
use App\Entity\SparePart;
use Doctrine\ORM\EntityManagerInterface;

class UniversalPageProvider
{
    /** @var EntityManagerInterface */
    private $em;

    /**
     * UniversalPageProvider constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function brandToArray(Brand $brand)
    {
        return  [
            "enBrand" => $brand->getBrandEn(),
            "name" => $brand->getName(),
            "url" => $brand->getUrl(),
            "logo" => $brand->getThumbnailLogo64(),
        ];
    }

    public function cityToArray(City $city)
    {
        return  [
            "name" => $city->getName(),
            "url" => $city->getUrl(),
            "logo" => $city->getLogo(),
        ];
    }

    public function sparePartToArray(SparePart $sparePart)
    {
        return  [
            "name" => $sparePart->getName(),
            "url" => $sparePart->getUrl(),
            "logo" => $sparePart->getLogo(),
        ];
    }

    public function getModels(Brand $brand)
    {
        /** @var Model[] $models */
        $models = $this->em->getRepository(Model::class)->findBy(
            [
                "isPopular" => true,
                "brand" => $brand,
            ],
            ["name" => "ASC"]);

        $parsedModels = [];

        foreach ($models as $model){
            $parsedModels[] = [
                "name" => $model->getName(),
                "url" => $model->getUrl(),
                "logo" => $model->getThumbnailLogo(),
            ];
        }

        return $parsedModels;
    }
}