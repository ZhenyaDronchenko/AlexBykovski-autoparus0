<?php

namespace App\Entity\Advert\AutoSparePart;

use App\Entity\Brand;
use App\Entity\Client\SellerAdvertDetail;
use App\Entity\DriveType;
use App\Entity\GearBoxType;
use App\Entity\Model;
use App\Entity\VehicleType;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Advert\AutoSparePart\AutoSparePartSpecificAdvertRepository")
 * @ORM\Table(name="auto_spare_part_specific_advert")
 */
class AutoSparePartSpecificAdvert
{
    const CONDITIONS_FORM = [
        "used" => "Б/У",
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
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $sparePart;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $engineType;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $engineCapacity;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $engineName;

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
     * @ORM\Column(name="condition_type", type="string")
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
     * @ORM\Column(type="float", scale=2, nullable=true)
     */
    private $cost;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", options={"default" : true})
     */
    private $isActive = 1;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $activatedAt;

    /**
     * AutoSparePartGeneralAdvert constructor.
     *
     * @param SellerAdvertDetail $advertDetail
     */
    public function __construct(SellerAdvertDetail $advertDetail)
    {
        $this->sellerAdvertDetail = $advertDetail;
        $this->createdAt = new DateTime();
        $this->activatedAt = new DateTime();
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
     * @return null|string
     */
    public function getSparePart(): ?string
    {
        return $this->sparePart;
    }

    /**
     * @param null|string $sparePart
     */
    public function setSparePart(?string $sparePart): void
    {
        $this->sparePart = $sparePart;
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

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     */
    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return DateTime
     */
    public function getActivatedAt(): DateTime
    {
        return $this->activatedAt;
    }

    /**
     * @param DateTime $activatedAt
     */
    public function setActivatedAt(DateTime $activatedAt): void
    {
        $this->activatedAt = $activatedAt;
    }

    public function createClone()
    {
        $advert = new AutoSparePartSpecificAdvert($this->sellerAdvertDetail);

        $advert->setBrand($this->brand);
        $advert->setModel($this->model);
        $advert->setYear($this->year);
        $advert->setSparePart($this->sparePart);
        $advert->setEngineType($this->engineType);
        $advert->setEngineCapacity($this->engineCapacity);
        $advert->setEngineName($this->engineName);
        $advert->setGearBoxType($this->gearBoxType);
        $advert->setVehicleType($this->vehicleType);
        $advert->setDriveType($this->driveType);

        return $advert;
    }

    public function toArray()
    {
        return [
            "id" => $this->id,
            "brand" => $this->brand->getName(),
            "model" => $this->model->getName(),
            "sparePart" => $this->sparePart,
            "year" => $this->year,
            "engineType" => $this->engineType,
            "engineCapacity" => $this->engineCapacity,
            "engineName" => $this->engineName,
            "gearBoxType" => $this->gearBoxType ? $this->gearBoxType->getType() : "",
            "vehicleType" => $this->vehicleType ? $this->vehicleType->getType() : "",
            "driveType" => $this->driveType ? $this->driveType->getType() : "",
            "condition" => self::CONDITIONS_FORM[$this->condition],
            "stockType" => self::STOCK_TYPES_FORM[$this->stockType],
            "sparePartNumber" => $this->sparePartNumber,
            "comment" => $this->comment,
            "image" => $this->image ? '/images/' . $this->image : "",
            "cost" => $this->cost,
            "isActive" => $this->isActive,
            "activatedAt" => $this->activatedAt->format("d.m.Y"),
        ];
    }
}