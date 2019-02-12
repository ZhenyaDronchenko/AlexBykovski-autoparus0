<?php

namespace App\Provider\Form;

use App\Entity\Brand;
use App\Entity\DriveType;
use App\Entity\Engine;
use App\Entity\EngineType;
use App\Entity\GearBoxType;
use App\Entity\Model;
use App\Entity\SparePart;
use App\Entity\VehicleType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class ClientCarProvider
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

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
            $choices[$brand["name"]] = $brand["id"];
        }

        return $choices;
    }

    public function getModels($brand, $isAll = false)
    {
        $choices = ["Выбрать" => ''];

        if($isAll){
            $models = $this->em->getRepository(Model::class)->findAllModelNames();

            foreach ($models as $model) {
                $choices[$model["name"]] = $model["id"];
            }
        }
        else {
            if (is_string($brand)) {
                $brand = $this->em->getRepository(Brand::class)->find($brand);
            }

            if ($brand instanceof Brand) {
                $models = $this->em->getRepository(Model::class)->findModelNamesByBrand($brand, true);

                foreach ($models as $model) {
                    $choices[$model["name"]] = $model["id"];
                }
            }
        }

        return $choices;
    }

    public function getYears($model, $isAll = false)
    {
        $choices = ["Выбрать" => 0];

        if($isAll){
            $currYear = (int)(new DateTime())->format("Y");

            foreach (range($currYear - 1000, $currYear + 1000) as $year){
                $choices[$year] = $year;
            }
        }
        elseif($model instanceof Model &&
            ($from = $model->getTechnicalData()->getYearFrom()) && ($to = $model->getTechnicalData()->getYearTo()) &&
            $to > $from){

            foreach (range($from, $to) as $year){
                $choices[$year] = $year;
            }
        }

        return $choices;
    }

    public function getVehicleTypes($model, $isAll = false)
    {
        $choices = ["Выбрать" => ''];

        if($isAll){
            $vehicleTypes = $this->em->getRepository(VehicleType::class)->findAllVehicleTypes();

            foreach ($vehicleTypes as $vehicleType) {
                $choices[$vehicleType["type"]] = $vehicleType["id"];
            }
        }
        elseif($model instanceof Model) {
            /** @var VehicleType $vehicleType */
            foreach ($model->getTechnicalData()->getVehicleTypes() as $vehicleType) {
                $choices[$vehicleType->getType()] = $vehicleType->getId();
            }
        }

        return $choices;
    }

    public function getEngineTypes($model, $isAll = false)
    {
        $choices = ["Выбрать" => ''];

        if($isAll){
            $engineTypes = $this->em->getRepository(EngineType::class)->findAllEngineTypes();

            foreach ($engineTypes as $engineType) {
                $choices[$engineType["type"]] = $engineType["id"];
            }
        }
        elseif($model instanceof Model){
            /** @var EngineType $engineType */
            foreach ($model->getTechnicalData()->getEngineTypes() as $engineType){
                $choices[$engineType->getType()] = (string)$engineType->getId();
            }
        }

        return $choices;
    }

    public function getCapacities($model, $engineType, $isAll = false)
    {
        $choices = [];

        if($isAll){
            $engines = $this->em->getRepository(Engine::class)->findAllCapacities();

            /** @var Engine $engine */
            foreach ($engines as $engine){
                $choices[(string)$engine["capacity"]] = (string)$engine["capacity"];
            }
        }
        elseif($model instanceof Model && $engineType instanceof EngineType){

            /** @var Engine $engine */
            foreach ($model->getTechnicalData()->getEnginesByType($engineType->getType()) as $engine){
                $choices[(string)$engine->getCapacity()] = (string)$engine->getCapacity();
            }

            asort($choices);
        }

        $choices = array_merge(["" => ''], $choices);

        return $choices;
    }

    public function getEngineNames($model, $engineType, $capacity, $isAll = false)
    {
        $choices = [];

        if($isAll){
            $engines = $this->em->getRepository(Engine::class)->findAllNames();

            /** @var Engine $engine */
            foreach ($engines as $engine){
                $choices[(string)$engine["name"]] = (string)$engine["name"];
            }
        }
        elseif($model instanceof Model && $engineType){

            /** @var Engine $engine */
            foreach ($model->getTechnicalData()->getEnginesByType($engineType) as $engine){
                if($capacity && $engine->getCapacity() !== $capacity){
                    continue;
                }

                $choices[(string)$engine->getName()] = (string)$engine->getName();
            }

            asort($choices);
        }

        $choices = array_merge(["" => ''], $choices);

        return $choices;
    }

    public function getGearBoxTypes($model, $isAll = false)
    {
        $choices = ["Выбрать" => ''];

        if($isAll){
            $gearBoxTypes = $this->em->getRepository(GearBoxType::class)->findAllGearBoxTypes();

            foreach ($gearBoxTypes as $gearBoxType) {
                $choices[$gearBoxType["type"]] = $gearBoxType["id"];
            }
        }
        elseif($model instanceof Model){
            /** @var GearBoxType $gearBoxType */
            foreach ($model->getTechnicalData()->getGearBoxTypes() as $gearBoxType){
                $choices[$gearBoxType->getType()] = $gearBoxType->getId();
            }
        }

        return $choices;
    }

    public function getDriveTypes($model, $isAll = false)
    {
        $choices = ["Выбрать" => ''];

        if($isAll){
            $driveTypes = $this->em->getRepository(DriveType::class)->findAllDriveTypes();

            foreach ($driveTypes as $driveType) {
                $choices[$driveType["type"]] = $driveType["id"];
            }
        }
        elseif($model instanceof Model){
            /** @var DriveType $driveType */
            foreach ($model->getTechnicalData()->getDriveTypes() as $driveType){
                $choices[$driveType->getType()] = (string)$driveType->getId();
            }
        }

        return $choices;
    }
}