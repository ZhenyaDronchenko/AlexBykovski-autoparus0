<?php

namespace App\Entity\Client;


use App\Entity\Advert\AutoSparePart\AutoSparePartGeneralAdvert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="seller_advert_detail")
 */
class SellerAdvertDetail
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
     * @var SellerData
     *
     * One SellerAdvertDetail has One SellerData.
     * @ORM\OneToOne(targetEntity="SellerData", inversedBy="advertDetail")
     * @ORM\JoinColumn(name="seller_data_id", referencedColumnName="id")
     */
    private $sellerData;

    /**
     * @var Collection
     *
     * One SellerAdvertDetail has Many AutoSparePartGeneralAdverts.
     * @ORM\OneToMany(targetEntity="App\Entity\Advert\AutoSparePart\AutoSparePartGeneralAdvert", mappedBy="sellerAdvertDetail")
     */
    private $autoSparePartGeneralAdverts;

    /**
     * SellerAdvertDetail constructor.
     */
    public function __construct()
    {
        $this->autoSparePartGeneralAdverts = new ArrayCollection();
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
     * @return SellerData
     */
    public function getSellerData(): SellerData
    {
        return $this->sellerData;
    }

    /**
     * @param SellerData $sellerData
     */
    public function setSellerData(SellerData $sellerData): void
    {
        $this->sellerData = $sellerData;
    }

    /**
     * @return Collection
     */
    public function getAutoSparePartGeneralAdverts(): Collection
    {
        return $this->autoSparePartGeneralAdverts;
    }

    /**
     * @param Collection $autoSparePartGeneralAdverts
     */
    public function setAutoSparePartGeneralAdverts(Collection $autoSparePartGeneralAdverts): void
    {
        $this->autoSparePartGeneralAdverts = $autoSparePartGeneralAdverts;
    }

    public function getAutoSparePartGeneralAdvertsBrands($isOnlyIds = false)
    {
        $brands = [];

        /** @var AutoSparePartGeneralAdvert $advert */
        foreach ($this->autoSparePartGeneralAdverts as $advert){
            if($isOnlyIds){
                $brands[] = $advert->getBrand() ? $advert->getBrand()->getId() : null;
            }
            else{
                $brands[] = $advert->getBrand();
            }
        }

        return $brands;
    }
}