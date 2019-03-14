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

    public function getMailPageTitle()
    {
        $page = $this->em->getRepository(MainPage::class)->findAll()[0];

        if(!$page instanceof MainPage){
            return "";
        }

        return $page->getTitle();
    }

    public function getBrands()
    {
        /** @var Brand[] $brands */
        $brands = $this->em->getRepository(Brand::class)->findBy(["popular" => true], ["name" => "ASC"]);

        $parsedBrands = [];

        foreach ($brands as $brand){
            $parsedBrands[] = [
                "enBrand" => $brand->getBrandEn(),
                "name" => $brand->getName(),
                "url" => $brand->getUrl(),
                "logo" => $brand->getThumbnailLogo32(),
            ];
        }

        return $parsedBrands;
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