<?php

namespace App\Provider\Form;

use App\Entity\Brand;
use App\Entity\Model;
use App\Entity\SparePart;
use Doctrine\ORM\EntityManagerInterface;

class AutoAdvertDataProvider
{
    /** @var EntityManagerInterface */
    private $em;

    /**
     * AutoAdvertDataProvider constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getSpareParts()
    {
        $choices = [];

        $spareParts = $this->em->getRepository(SparePart::class)->findAllForAdvert();

        foreach ($spareParts as $sparePart){
            $choices[$sparePart["name"]] = $sparePart["id"];
        }

        return $choices;
    }

    public function getModels(Brand $brand = null)
    {
        $choices = [];

        if ($brand instanceof Brand) {
            $models = $this->em->getRepository(Model::class)->findModelNamesByBrand($brand, true);

            foreach ($models as $model) {
                $choices[$model["name"]] = $model["id"];
            }
        }

        return $choices;
    }

    public function getBrands($exceptBrandIds, $isAllUsed = false)
    {
        if(!$isAllUsed){
            $choices["Все марки"] = 0;
        }

        if(count($exceptBrandIds)){
            $brands = $this->em->getRepository(Brand::class)->findAllForAdvert($exceptBrandIds);
        }
        else{
            $brands = $this->em->getRepository(Brand::class)->findAllOnlyField("brandEn", true);
        }

        foreach ($brands as $brand){
            $choices[$brand["brandEn"]] = (string)$brand["id"];
        }

        return $choices;
    }

    public function getBrandById($id){
        return $this->em->getRepository(Brand::class)->find($id);
    }
}