<?php

namespace App\Entity\Advert\AutoSparePart;

use App\Entity\Brand;
use App\Entity\Client\SellerAdvertDetail;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="auto_spare_part_general_advert")
 */
class AutoSparePartGeneralAdvert
{
    const CONDITIONS = [
        "used" => "БУ запчасти (товары)",
        "new" => "Новые товары (запчасти)",
        "rebuilt" => "Восстановленные",
    ];

    const STOCK_TYPES = [
        "in_stock" => "Работаем по наличию",
        "under_order" => "Работаем под заказ",
        "check_availability" => "Необходимо уточнение наличия товара",
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

    // if it null and isBrandAdded = 1 - it means "all brands"
    /**
     * @var Brand|null|string
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Brand")
     * @ORM\JoinColumn(name="brand_id", referencedColumnName="id", nullable=true)
     */
    private $brand;

    /**
     * @var Collection
     *
     * Many AutoSparePartGeneralAdverts have Many Models.
     * @ORM\ManyToMany(targetEntity="App\Entity\Model")
     * @ORM\JoinTable(name="auto_spare_part_general_adverts_models",
     *      joinColumns={@ORM\JoinColumn(name="advert_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="model_id", referencedColumnName="id")}
     *      )
     */
    private $models;

    /**
     * @var Collection
     *
     * Many AutoSparePartGeneralAdverts have Many SpareParts.
     * @ORM\ManyToMany(targetEntity="App\Entity\SparePart")
     * @ORM\JoinTable(name="auto_spare_part_general_adverts_spare_parts",
     *      joinColumns={@ORM\JoinColumn(name="advert_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="spare_part_id", referencedColumnName="id")}
     *      )
     */
    private $spareParts;

    /**
     * @var string|null
     *
     * @ORM\Column(name="comment_advert", type="text", nullable=true)
     */
    private $comment;

    /**
     * @var SellerAdvertDetail
     *
     * Many Features have One SellerAdvertDetail.
     * @ORM\ManyToOne(targetEntity="App\Entity\Client\SellerAdvertDetail", inversedBy="autoSparePartGeneralAdverts")
     * @ORM\JoinColumn(name="seller_advert_detail_id", referencedColumnName="id")
     */
    private $sellerAdvertDetail;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $isBrandAdded = 0;

    /**
     * AutoSparePartGeneralAdvert constructor.
     *
     * @param SellerAdvertDetail $advertDetail
     */
    public function __construct(SellerAdvertDetail $advertDetail)
    {
        $this->sellerAdvertDetail = $advertDetail;
        $this->models = new ArrayCollection();
        $this->spareParts = new ArrayCollection();
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
     * @return Brand|null|string
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * @param Brand|null|string $brand
     */
    public function setBrand($brand): void
    {
        $this->brand = $brand;
        $this->isBrandAdded = true;
    }

    /**
     * @return Collection
     */
    public function getModels(): Collection
    {
        return $this->models;
    }

    /**
     * @param Collection $models
     */
    public function setModels(Collection $models): void
    {
        $this->models = $models;
    }

    /**
     * @return Collection
     */
    public function getSpareParts(): Collection
    {
        return $this->spareParts;
    }

    /**
     * @param Collection $spareParts
     */
    public function setSpareParts(Collection $spareParts): void
    {
        $this->spareParts = $spareParts;
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
     * @return bool
     */
    public function isBrandAdded(): bool
    {
        return $this->isBrandAdded;
    }

    /**
     * @param bool $isBrandAdded
     */
    public function setIsBrandAdded(bool $isBrandAdded): void
    {
        $this->isBrandAdded = $isBrandAdded;
    }
}