<?php

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="model_technical_data")
 */
class ModelTechnicalData
{
    /**
     * @var integer|null
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $yearFrom = 0;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $yearTo = 0;

    /**
     * @var Collection
     *
     * Many ModelTechnicalDatum have Many EngineTypes.
     * @ORM\ManyToMany(targetEntity="EngineType")
     * @ORM\JoinTable(name="model_datum_engine_types",
     *      joinColumns={@ORM\JoinColumn(name="model_technical_data_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="engine_type_id", referencedColumnName="id")}
     *      )
     */
    private $engineTypes; // Entity

    /**
     * @var Collection
     *
     * Many ModelTechnicalDatum have Many DriveTypes.
     * @ORM\ManyToMany(targetEntity="DriveType")
     * @ORM\JoinTable(name="model_datum_drive_types",
     *      joinColumns={@ORM\JoinColumn(name="model_technical_data_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="drive_type_id", referencedColumnName="id")}
     *      )
     */
    private $driveTypes; // Entity

    /**
     * @var Collection
     *
     * Many ModelTechnicalDatum have Many GearBoxTypes.
     * @ORM\ManyToMany(targetEntity="GearBoxType")
     * @ORM\JoinTable(name="model_datum_gear_box_types",
     *      joinColumns={@ORM\JoinColumn(name="model_technical_data_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="gear_box_type_id", referencedColumnName="id")}
     *      )
     */
    private $gearBoxTypes; // Entity

    /**
     * @var Collection
     *
     * Many ModelTechnicalDatum have Many Engines.
     * @ORM\ManyToMany(targetEntity="Engine", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="model_datum_engines",
     *      joinColumns={@ORM\JoinColumn(name="model_technical_data_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="engine_id", referencedColumnName="id")}
     *      )
     */
    private $engines; // Entity

    /**
     * @var Collection
     *
     * Many ModelTechnicalDatum have Many VehicleTypes.
     * @ORM\ManyToMany(targetEntity="VehicleType")
     * @ORM\JoinTable(name="model_datum_vehicle_types",
     *      joinColumns={@ORM\JoinColumn(name="model_technical_data_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="vehicle_type_id", referencedColumnName="id")}
     *      )
     */
    private $vehicleTypes; // Entity

    /**
     * @var VehicleCategory|null
     *
     * @ORM\ManyToOne(targetEntity="VehicleCategory")
     * @ORM\JoinColumn(name="vehicle_category_id", referencedColumnName="id")
     */
    private $vehicleCategory; // Entity

    /**
     * @var Model
     *
     * One ModelTechnicalData has One Model.
     * @ORM\OneToOne(targetEntity="Model", inversedBy="technicalData")
     * @ORM\JoinColumn(name="model_id", referencedColumnName="id")
     */
    private $model;

    /**
     * ModelTechnicalData constructor.
     */
    public function __construct()
    {
        $this->engineTypes = new ArrayCollection();
        $this->driveTypes = new ArrayCollection();
        $this->gearBoxTypes = new ArrayCollection();
        $this->engines = new ArrayCollection();
        $this->vehicleTypes = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getYearFrom(): int
    {
        return $this->yearFrom;
    }

    /**
     * @param int $yearFrom
     */
    public function setYearFrom(int $yearFrom): void
    {
        $this->yearFrom = $yearFrom;
    }

    /**
     * @return int
     */
    public function getYearTo(): int
    {
        return $this->yearTo;
    }

    /**
     * @param int $yearTo
     */
    public function setYearTo(int $yearTo): void
    {
        $this->yearTo = $yearTo;
    }

    /**
     * @return Collection
     */
    public function getEngineTypes(): Collection
    {
        return $this->engineTypes;
    }

    /**
     * @param Collection $engineTypes
     */
    public function setEngineTypes(Collection $engineTypes): void
    {
        $this->engineTypes = $engineTypes;
    }

    /**
     * @return Collection
     */
    public function getDriveTypes(): Collection
    {
        return $this->driveTypes;
    }

    /**
     * @param Collection $driveTypes
     */
    public function setDriveTypes(Collection $driveTypes): void
    {
        $this->driveTypes = $driveTypes;
    }

    /**
     * @return Collection
     */
    public function getGearBoxTypes(): Collection
    {
        return $this->gearBoxTypes;
    }

    /**
     * @param Collection $gearBoxTypes
     */
    public function setGearBoxTypes(Collection $gearBoxTypes): void
    {
        $this->gearBoxTypes = $gearBoxTypes;
    }

    /**
     * @return Collection
     */
    public function getEngines(): Collection
    {
        return $this->engines;
    }

    /**
     * @param Collection $engines
     */
    public function setEngines(Collection $engines): void
    {
        $this->engines = $engines;
    }

    /**
     * @return Collection
     */
    public function getVehicleTypes(): Collection
    {
        return $this->vehicleTypes;
    }

    /**
     * @param Collection $vehicleTypes
     */
    public function setVehicleTypes(Collection $vehicleTypes): void
    {
        $this->vehicleTypes = $vehicleTypes;
    }

    /**
     * @return VehicleCategory|null
     */
    public function getVehicleCategory(): ?VehicleCategory
    {
        return $this->vehicleCategory;
    }

    /**
     * @param VehicleCategory|null $vehicleCategory
     */
    public function setVehicleCategory(?VehicleCategory $vehicleCategory): void
    {
        $this->vehicleCategory = $vehicleCategory;
    }

    /**
     * @return Model
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * @param Model $model
     */
    public function setModel(Model $model): void
    {
        $this->model = $model;
    }

    public function getEnginesByType($type){
        return array_filter($this->engines->getValues(), function(Engine $item) use ($type){
            return $item->getType() == $type;
        });
    }
}