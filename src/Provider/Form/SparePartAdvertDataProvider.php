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

class SparePartAdvertDataProvider extends ClientCarProvider
{
    /**
     * SparePartAdvertDataProvider constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em);
    }

    public function getYears($model, $isAll = false)
    {
        $choices = ["" => 0];

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

    public function getEngineTypes($model, $isAll = false)
    {
        $choices = ["Выбрать" => ''];

        if($isAll){
            $engineTypes = $this->em->getRepository(EngineType::class)->findAllEngineTypes();

            foreach ($engineTypes as $engineType) {
                $choices[$engineType["type"]] = $engineType["type"];
            }
        }
        elseif($model instanceof Model){
            /** @var EngineType $engineType */
            foreach ($model->getTechnicalData()->getEngineTypes() as $engineType){
                $choices[$engineType->getType()] = (string)$engineType->getType();
            }
        }

        return $choices;
    }

    public function getEngineCapacities($model, $engineType, $isAll = false)
    {
        $choices = [];

        if($isAll){
            $engines = $this->em->getRepository(Engine::class)->findAllCapacities();

            /** @var Engine $engine */
            foreach ($engines as $engine){
                $choices[(string)$engine["capacity"]] = (string)$engine["capacity"];
            }
        }
        elseif($model instanceof Model && $engineType){

            /** @var Engine $engine */
            foreach ($model->getTechnicalData()->getEnginesByType($engineType) as $engine){
                $choices[(string)$engine->getCapacity()] = (string)$engine->getCapacity();
            }

            asort($choices);
        }

        $choices = array_merge(["" => ''], $choices);

        return $choices;
    }

    public function getEngineNames($model, $engineType, $isAll = false)
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

    public function getSpareParts()
    {
        $spareParts = $this->em->getRepository(SparePart::class)->findAllOnlyField("name", true);

        $choices = [];

        foreach ($spareParts as $sparePart){
            $choices[$sparePart["name"]] = $sparePart["id"];
        }

        return $choices;
    }
}