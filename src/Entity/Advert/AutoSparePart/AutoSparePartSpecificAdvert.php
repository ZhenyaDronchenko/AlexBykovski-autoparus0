<?php

namespace App\Entity\Advert\AutoSparePart;

use App\Entity\Brand;
use App\Entity\Client\SellerAdvertDetail;
use App\Entity\DriveType;
use App\Entity\Engine;
use App\Entity\GearBoxType;
use App\Entity\Model;
use App\Entity\SparePart;
use App\Entity\VehicleType;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="auto_spare_part_specific_advert")
 */
class AutoSparePartSpecificAdvert
{
    const CONDITIONS_FORM = [
        "used" => "Б / У",
        "new" => "Новая(й)",
    ];

    const STOCK_TYPES_FORM = [
        "in_stock" => "В наличии",
        "under_order" => "На заказ",
    ];

    /**
     * @var integer|null
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var SellerAdvertDetail
     *
     * Many AutoSparePartSpecificAdverts have One SellerAdvertDetail.
     * @ORM\ManyToOne(targetEntity="App\Entity\Client\SellerAdvertDetail", inversedBy="autoSparePartSpecificAdverts")
     * @ORM\JoinColumn(name="seller_advert_detail_id", referencedColumnName="id")
     */
    private $sellerAdvertDetail;

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
     * @ORM\Column(type="integer")
     */
    private $year;

    /**
     * @var SparePart|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\SparePart")
     * @ORM\JoinColumn(name="spare_part_id", referencedColumnName="id")
     */
    private $sparePart;

    /**
     * @var Engine|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Engine")
     * @ORM\JoinColumn(name="engine_id", referencedColumnName="id", nullable=true)
     */
    private $engine;

    /**
     * @var GearBoxType|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\GearBoxType")
     * @ORM\JoinColumn(name="gear_box_type_id", referencedColumnName="id", nullable=true)
     */
    private $gearBoxType;

    /**
     * @var VehicleType|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\VehicleType")
     * @ORM\JoinColumn(name="vehicle_type_id", referencedColumnName="id", nullable=true)
     */
    private $vehicleType;

    /**
     * @var DriveType|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\DriveType")
     * @ORM\JoinColumn(name="drive_type_id", referencedColumnName="id", nullable=true)
     */
    private $driveType;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $condition;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $stockType;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $sparePartNumber;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $image;

    /**
     * @var float|null
     *
     * @ORM\Column(type="float", scale=2)
     */
    private $cost;

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
     * @return SellerAdvertDetail
     */
    public function getSellerAdvertDetail(): SellerAdvertDetail
    {
        return $this->sellerAdvertDetail;
    }

    /**
     * @param SellerAdvertDetail $sellerAdvertDetail
     */
    public function setSellerAdvertDetail(SellerAdvertDetail $sellerAdvertDetail): void
    {
        $this->sellerAdvertDetail = $sellerAdvertDetail;
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
     * @return SparePart|null
     */
    public function getSparePart(): ?SparePart
    {
        return $this->sparePart;
    }

    /**
     * @param SparePart|null $sparePart
     */
    public function setSparePart(?SparePart $sparePart): void
    {
        $this->sparePart = $sparePart;
    }

    /**
     * @return Engine|null
     */
    public function getEngine(): ?Engine
    {
        return $this->engine;
    }

    /**
     * @param Engine|null $engine
     */
    public function setEngine(?Engine $engine): void
    {
        $this->engine = $engine;
    }

    /**
     * @return GearBoxType|null
     */
    public function getGearBoxType(): ?GearBoxType
    {
        return $this->gearBoxType;
    }

    /**
     * @param GearBoxType|null $gearBoxType
     */
    public function setGearBoxType(?GearBoxType $gearBoxType): void
    {
        $this->gearBoxType = $gearBoxType;
    }

    /**
     * @return VehicleType|null
     */
    public function getVehicleType(): ?VehicleType
    {
        return $this->vehicleType;
    }

    /**
     * @param VehicleType|null $vehicleType
     */
    public function setVehicleType(?VehicleType $vehicleType): void
    {
        $this->vehicleType = $vehicleType;
    }

    /**
     * @return DriveType|null
     */
    public function getDriveType(): ?DriveType
    {
        return $this->driveType;
    }

    /**
     * @param DriveType|null $driveType
     */
    public function setDriveType(?DriveType $driveType): void
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
    public function getSparePartNumber(): ?string
    {
        return $this->sparePartNumber;
    }

    /**
     * @param null|string $sparePartNumber
     */
    public function setSparePartNumber(?string $sparePartNumber): void
    {
        $this->sparePartNumber = $sparePartNumber;
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

    /**
     * @return float|null
     */
    public function getCost(): ?float
    {
        return $this->cost;
    }

    /**
     * @param float|null $cost
     */
    public function setCost(?float $cost): void
    {
        $this->cost = $cost;
    }
}