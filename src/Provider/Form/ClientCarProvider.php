<?php

namespace App\Provider\Form;

use App\Entity\Brand;
use App\Entity\Engine;
use App\Entity\EngineType;
use App\Entity\Model;
use App\Entity\VehicleType;
use Doctrine\ORM\EntityManagerInterface;

class ClientCarProvider
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * ClientCarProvider constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    public function getAllBrands()
    {
        $brands = $this->em->getRepository(Brand::class)->findAllOnlyField("name", true);

        $choices = ["Выбрать" => ''];

        foreach ($brands as $brand){
            $choices[$brand["name"]] = $brand["name"];
        }

        return $choices;
    }

    public function getModels($brand)
    {
        $choices = ["Выбрать" => ''];

        if($brand instanceof Brand){
            $brands = $this->em->getRepository(Model::class)->findModelNamesByBrand($brand, true);

            foreach ($brands as $brand){
                $choices[$brand["name"]] = $brand["name"];
            }
        }

        return $choices;
    }

    public function getYears($model)
    {
        $choices = ["Выбрать" => 0];

        if($model instanceof Model &&
            ($from = $model->getTechnicalData()->getYearFrom()) && ($to = $model->getTechnicalData()->getYearTo()) &&
            $to > $from){

            foreach (range($from, $to) as $year){
                $choices[$year] = $year;
            }
        }

        return $choices;
    }

    public function getVehicleTypes($model)
    {
        $choices = ["Выбрать" => ''];

        if($model instanceof Model){
            /** @var VehicleType $vehicleType */
            foreach ($model->getTechnicalData()->getVehicleTypes() as $vehicleType){
                $choices[$vehicleType->getType()] = $vehicleType->getType();
            }
        }

        return $choices;
    }

    public function getEngineTypes($model)
    {
        $choices = ["Выбрать" => ''];

        if($model instanceof Model){
            /** @var EngineType $engineType */
            foreach ($model->getTechnicalData()->getEngineTypes() as $engineType){
                $choices[$engineType->getType()] = $engineType->getType();
            }
        }

        return $choices;
    }

    public function getCapacities($model, $engineType)
    {
        $choices = [];

        if($model instanceof Model && $engineType instanceof EngineType){

            /** @var Engine $engine */
            foreach ($model->getTechnicalData()->getEnginesByType($engineType->getType()) as $engine){
                $choices[$engine->getCapacity()] = $engine->getCapacity();
            }

            sort($choices);
        }

        array_unshift($choices, ["Выбрать" => '']);

        return $choices;
    }
}