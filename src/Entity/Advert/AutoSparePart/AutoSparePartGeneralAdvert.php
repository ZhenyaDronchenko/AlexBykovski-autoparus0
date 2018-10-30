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
     * @var integer
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $condition;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $stockType;

    /**
     * @var Brand|null
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
     * @var SellerAdvertDetail
     *
     * Many Features have One SellerAdvertDetail.
     * @ORM\ManyToOne(targetEntity="App\Entity\Client\SellerAdvertDetail", inversedBy="autoSparePartGeneralAdverts")
     * @ORM\JoinColumn(name="seller_advert_detail_id", referencedColumnName="id")
     */
    private $sellerAdvertDetail;

    /**
     * AutoSparePartGeneralAdvert constructor.
     */
    public function __construct()
    {
        $this->models = new ArrayCollection();
        $this->spareParts = new ArrayCollection();
    }

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
     * @return string
     */
    public function getCondition(): string
    {
        return $this->condition;
    }

    /**
     * @param string $condition
     */
    public function setCondition(string $condition): void
    {
        $this->condition = $condition;
    }

    /**
     * @return string
     */
    public function getStockType(): string
    {
        return $this->stockType;
    }

    /**
     * @param string $stockType
     */
    public function setStockType(string $stockType): void
    {
        $this->stockType = $stockType;
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
}