<?php

namespace App\Entity\Advert\AutoSparePart;

use App\Entity\Brand;
use App\Entity\Client\SellerAdvertDetail;
use App\Entity\DriveType;
use App\Entity\GearBoxType;
use App\Entity\Model;
use App\Entity\VehicleType;
use App\Type\AutoSetType;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Advert\AutoSparePart\AutoSparePartSpecificAdvertRepository")
 * @ORM\Table(name="auto_spare_part_specific_advert")
 */
class AutoSparePartSpecificAdvert
{
    const IN_STOCK_TYPE = "in_stock";
    const UNDER_ORDER_TYPE = "under_order";

    const USED_TYPE = "used";

    const CONDITIONS_FORM = [
        "used" => "Б/У",
        "new" => "Новая(й)",
    ];

    const STOCK_TYPES_FORM = [
        self::IN_STOCK_TYPE => "В наличии",
        self::UNDER_ORDER_TYPE => "На заказ",
    ];

    const CONDITIONS_CLIENT_VIEW = [
        "used" => "Б/У",
        "new" => "Новые",
    ];

    const STOCK_TYPES_CLIENT_VIEW = [
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
     * @var Model|null|string
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
     * @var GearBoxType|null|string
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\GearBoxType")
     * @ORM\JoinColumn(name="gear_box_type_id", referencedColumnName="id", nullable=true)
     */
    private $gearBoxType;

    /**
     * @var VehicleType|null|string
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\VehicleType")
     * @ORM\JoinColumn(name="vehicle_type_id", referencedColumnName="id", nullable=true)
     */
    private $vehicleType;

    /**
     * @var DriveType|null|string
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
     * @ORM\Column(type="string", length=100, nullable=true)
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
     * @ORM\Column(type="text", nullable=true)
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
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $activatedAt;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $currency;

    /**
     * AutoSparePartGeneralAdvert constructor.
     *
     * @param SellerAdvertDetail $advertDetail
     */
    public function __construct(SellerAdvertDetail $advertDetail)
    {
        $this->sellerAdvertDetail = $advertDetail;
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
        $this->activatedAt = new DateTime();
    }

    public function copyForImport()
    {
        $newAdvert = new AutoSparePartSpecificAdvert($this->sellerAdvertDetail);

        $newAdvert->setBrand($this->brand);
        $newAdvert->setModel($this->model);
        $newAdvert->setSparePart($this->sparePart);
        $newAdvert->setYear($this->year);
        $newAdvert->setEngineType($this->engineType);
        $newAdvert->setEngineCapacity($this->engineCapacity);
        $newAdvert->setGearBoxType($this->gearBoxType);
        $newAdvert->setVehicleType($this->vehicleType);
        $newAdvert->setSparePartNumber($this->sparePartNumber);
        $newAdvert->setImage($this->image);
        $newAdvert->setCost($this->cost);
        $newAdvert->setComment($this->comment);
        $newAdvert->setCurrency($this->currency);
        $newAdvert->setCondition($this->condition);
        $newAdvert->setStockType($this->stockType);

        return $newAdvert;
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
     * @param Model|null|string $model
     */
    public function setModel($model): void
    {
        $this->model = $model instanceof Model ? $model : null;
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
     * @param GearBoxType|null|string $gearBoxType
     */
    public function setGearBoxType($gearBoxType): void
    {
        $this->gearBoxType = $gearBoxType instanceof GearBoxType ? $gearBoxType : null;
    }

    /**
     * @return VehicleType|null
     */
    public function getVehicleType(): ?VehicleType
    {
        return $this->vehicleType;
    }

    /**
     * @param VehicleType|null|string $vehicleType
     */
    public function setVehicleType($vehicleType): void
    {
        $this->vehicleType = $vehicleType instanceof VehicleType ? $vehicleType : null;;
    }

    /**
     * @return DriveType|null
     */
    public function getDriveType(): ?DriveType
    {
        return $this->driveType;
    }

    /**
     * @param DriveType|null|string $driveType
     */
    public function setDriveType($driveType): void
    {
        $this->driveType = $driveType instanceof DriveType ? $driveType : null;;
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

    /**
     * @return null|string
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * @param null|string $currency
     */
    public function setCurrency(?string $currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime $updatedAt
     */
    public function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function createCloneByAuto()
    {
        $advert = new AutoSparePartSpecificAdvert($this->sellerAdvertDetail);

        $advert->setBrand($this->brand);
        $advert->setModel($this->model);
        $advert->setYear($this->year);
        $advert->setEngineType($this->engineType);
        $advert->setEngineCapacity($this->engineCapacity);
        $advert->setEngineName($this->engineName);
        $advert->setGearBoxType($this->gearBoxType);
        $advert->setVehicleType($this->vehicleType);
        $advert->setDriveType($this->driveType);
        $advert->setCondition($this->condition);
        $advert->setStockType($this->stockType);
        $advert->setComment($this->comment);

        return $advert;
    }

    public function createCloneBySparePart()
    {
        $advert = new AutoSparePartSpecificAdvert($this->sellerAdvertDetail);

        $advert->setBrand($this->brand);
        $advert->setCondition($this->condition);
        $advert->setStockType($this->stockType);
        $advert->setComment($this->comment);
        $advert->setSparePart($this->sparePart);

        return $advert;
    }

    public function setByAutoSet(AutoSetType $autoSet, $sparePart = null)
    {
        $this->brand = $autoSet->getBrand();
        $this->model = $autoSet->getModel();
        $this->year = $autoSet->getYear();
        $this->engineType = $autoSet->getBrand();
        $this->engineCapacity = $autoSet->getEngineCapacity();
        $this->engineName = $autoSet->getEngineName();
        $this->gearBoxType = $autoSet->getGearBoxType();
        $this->vehicleType = $autoSet->getVehicleType();
        $this->driveType = $autoSet->getDriveType();
        $this->condition = $autoSet->getCondition();
        $this->stockType = $autoSet->getStockType();
        $this->comment = $autoSet->getComment();

        $this->sparePart = $sparePart["name"];
        $this->cost = $sparePart["cost"];
    }

    public function toArray()
    {
        $imagePath = '/images/' . $this->image;

        if(strpos($this->image, "https") === 0 || strpos($this->image, "http") === 0){
            $imagePath = $this->image;
        }

        return [
            "id" => $this->id,
            "brand" => $this->brand->getName(),
            "model" => $this->model->getName(),
            "sparePart" => $this->sparePart,
            "brandUrl" => $this->brand->getUrl(),
            "modelUrl" => $this->model->getUrl(),
            "city" => $this->sellerAdvertDetail->getSellerData()->getSellerCompany()->getCity(),
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
            "image" => $imagePath,
            "cost" => $this->cost,
            "currency" => strtoupper($this->currency),
            "isActive" => $this->isActive,
            "activatedAt" => $this->activatedAt->format("d.m.Y"),
        ];
    }

    public function getStockTypeView()
    {
        return self::STOCK_TYPES_CLIENT_VIEW[$this->stockType];
    }

    public function getConditionTypeView()
    {
        return self::CONDITIONS_CLIENT_VIEW[$this->condition];
    }

    public function getConditionStockView()
    {
        return $this->getStockTypeView() . ', ' . $this->getConditionTypeView();
    }

    public function getEngineDescription($full = true, $nameInBrackets = true)
    {
        $description = "";

        if($this->engineCapacity){
            $description .= ' ' . $this->engineCapacity;
        }

        if($this->engineType){
            $description .= ' ' . $this->engineType;
        }

        if($this->engineName && $full){
            if($nameInBrackets){
                $description .= ' (' . $this->engineName . ')';
            }
            else{
                $description .= ' ' . $this->engineName . '';
            }
        }

        return $description;
    }
}