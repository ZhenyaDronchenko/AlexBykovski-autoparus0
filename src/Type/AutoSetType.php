<?php

namespace App\Type;

use App\Entity\Brand;
use App\Entity\DriveType;
use App\Entity\GearBoxType;
use App\Entity\Model;
use App\Entity\VehicleType;
use Doctrine\Common\Collections\Collection;

class AutoSetType
{
    /**
     * @var Brand|null
     */
    private $brand;

    /**
     * @var Model|null|string
     */
    private $model;

    /**
     * @var integer|null
     */
    private $year;

    /**
     * @var array|null
     */
    private $spareParts;

    /**
     * @var string|null
     */
    private $engineType;

    /**
     * @var string|null
     */
    private $engineCapacity;

    /**
     * @var string|null
     */
    private $engineName;

    /**
     * @var GearBoxType|null|string
     */
    private $gearBoxType;

    /**
     * @var VehicleType|null|string
     */
    private $vehicleType;

    /**
     * @var DriveType|null|string
     */
    private $driveType;

    /**
     * @var string|null
     */
    private $condition;

    /**
     * @var string|null
     */
    private $stockType;

    /**
     * @var string|null
     */
    private $comment;

    /**
     * @var string|null
     */
    private $image;

    /**
     * @return Brand|null
     */
    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    /**
     * @param Brand|null $brand
     */
    public function setBrand(?Brand $brand): void
    {
        $this->brand = $brand;
    }

    /**
     * @return Model|null|string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param Model|null|string $model
     */
    public function setModel($model): void
    {
        $this->model = $model;
    }

    /**
     * @return int|null
     */
    public function getYear(): ?int
    {
        return $this->year;
    }

    /**
     * @param int|null $year
     */
    public function setYear(?int $year): void
    {
        $this->year = $year;
    }

    /**
     * @return array|null
     */
    public function getSpareParts(): ?array
    {
        return $this->spareParts;
    }

    /**
     * @param array|null $spareParts
     */
    public function setSpareParts(?array $spareParts): void
    {
        $this->spareParts = $spareParts;
    }

    /**
     * @return null|string
     */
    public function getEngineType(): ?string
    {
        return $this->engineType;
    }

    /**
     * @param null|string $engineType
     */
    public function setEngineType(?string $engineType): void
    {
        $this->engineType = $engineType;
    }

    /**
     * @return null|string
     */
    public function getEngineCapacity(): ?string
    {
        return $this->engineCapacity;
    }

    /**
     * @param null|string $engineCapacity
     */
    public function setEngineCapacity(?string $engineCapacity): void
    {
        $this->engineCapacity = $engineCapacity;
    }

    /**
     * @return null|string
     */
    public function getEngineName(): ?string
    {
        return $this->engineName;
    }

    /**
     * @param null|string $engineName
     */
    public function setEngineName(?string $engineName): void
    {
        $this->engineName = $engineName;
    }

    /**
     * @return GearBoxType|null|string
     */
    public function getGearBoxType()
    {
        return $this->gearBoxType;
    }

    /**
     * @param GearBoxType|null|string $gearBoxType
     */
    public function setGearBoxType($gearBoxType): void
    {
        $this->gearBoxType = $gearBoxType;
    }

    /**
     * @return VehicleType|null|string
     */
    public function getVehicleType()
    {
        return $this->vehicleType;
    }

    /**
     * @param VehicleType|null|string $vehicleType
     */
    public function setVehicleType($vehicleType): void
    {
        $this->vehicleType = $vehicleType;
    }

    /**
     * @return DriveType|null|string
     */
    public function getDriveType()
    {
        return $this->driveType;
    }

    /**
     * @param DriveType|null|string $driveType
     */
    public function setDriveType($driveType): void
    {
        $this->driveType = $driveType;
    }

    /**
     * @return null|string
     */
    public function getCondition(): ?string
    {
        return $this->condition;
    }

    /**
     * @param null|string $condition
     */
    public function setCondition(?string $condition): void
    {
        $this->condition = $condition;
    }

    /**
     * @return null|string
     */
    public function getStockType(): ?string
    {
        return $this->stockType;
    }

    /**
     * @param null|string $stockType
     */
    public function setStockType(?string $stockType): void
    {
        $this->stockType = $stockType;
    }

    /**
     * @return null|string
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param null|string $comment
     */
    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }

    /**
     * @return null|string
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param null|string $image
     */
    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    public function clearForContinue()
    {
        $this->brand = null;
        $this->model = null;
        $this->year = null;
        $this->engineType = null;
        $this->engineCapacity = null;
        $this->engineName = null;
        $this->gearBoxType = null;
        $this->vehicleType = null;
        $this->driveType = null;
        $this->stockType = null;
        $this->condition = null;
        $this->image = null;
    }
}