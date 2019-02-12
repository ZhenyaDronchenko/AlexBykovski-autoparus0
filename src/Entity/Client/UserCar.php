<?php

namespace App\Entity\Client;

use App\Entity\Brand;
use App\Entity\DriveType;
use App\Entity\EngineType;
use App\Entity\GearBoxType;
use App\Entity\Model;
use App\Entity\VehicleType;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_car")
 */
class UserCar
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Brand|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Brand")
     * @ORM\JoinColumn(name="brand_id", referencedColumnName="id")
     */
    private $brand;

    /**
     * @var Model|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Model")
     * @ORM\JoinColumn(name="model_id", referencedColumnName="id")
     */
    private $model;

    /**
     * @var integer|null
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $year;

    /**
     * @var VehicleType|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\VehicleType")
     * @ORM\JoinColumn(name="vehicle_id", referencedColumnName="id")
     */
    private $vehicle;

    /**
     * @var EngineType|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\EngineType")
     * @ORM\JoinColumn(name="engine_type_id", referencedColumnName="id")
     */
    private $engineType;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $capacity;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $engineName;

    /**
     * @var GearBoxType|null|string
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\GearBoxType")
     * @ORM\JoinColumn(name="gear_box_type_id", referencedColumnName="id", nullable=true)
     */
    private $gearBoxType;

    /**
     * @var DriveType|null|string
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\DriveType")
     * @ORM\JoinColumn(name="drive_type_id", referencedColumnName="id", nullable=true)
     */
    private $driveType;

    /**
     * @var Client
     *
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="cars")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    private $client;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

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
     * @return Model|null
     */
    public function getModel(): ?Model
    {
        return $this->model;
    }

    /**
     * @param Model|null $model
     */
    public function setModel(?Model $model): void
    {
        $this->model = $model;
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @param Client $client
     */
    public function setClient(Client $client): void
    {
        $this->client = $client;
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
     * @return VehicleType|null
     */
    public function getVehicle(): ?VehicleType
    {
        return $this->vehicle;
    }

    /**
     * @param VehicleType|null $vehicle
     */
    public function setVehicle(?VehicleType $vehicle): void
    {
        $this->vehicle = $vehicle;
    }

    /**
     * @return EngineType|null
     */
    public function getEngineType(): ?EngineType
    {
        return $this->engineType;
    }

    /**
     * @param EngineType|null $engineType
     */
    public function setEngineType(?EngineType $engineType): void
    {
        $this->engineType = $engineType;
    }

    /**
     * @return null|string
     */
    public function getCapacity(): ?string
    {
        return $this->capacity;
    }

    /**
     * @param null|string $capacity
     */
    public function setCapacity(?string $capacity): void
    {
        $this->capacity = $capacity;
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
}