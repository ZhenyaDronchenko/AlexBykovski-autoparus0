<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="seller")
 */
class Seller extends User
{
    /**
     * @var SellerCompany|null
     *
     * One Seller has One SellerCompany.
     * @ORM\OneToOne(targetEntity="SellerCompany", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     */
    private $sellerCompany;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $isServiceStation = 0;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $isNews = 0;

    /**
     * Seller constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->addRole(User::ROLE_SELLER);
        $this->sellerCompany = new SellerCompany();
    }

    /**
     * @return SellerCompany|null
     */
    public function getSellerCompany(): ?SellerCompany
    {
        return $this->sellerCompany;
    }

    /**
     * @param SellerCompany|null $sellerCompany
     */
    public function setSellerCompany(?SellerCompany $sellerCompany): void
    {
        $this->sellerCompany = $sellerCompany;
    }

    /**
     * @return bool
     */
    public function isServiceStation(): bool
    {
        return $this->isServiceStation;
    }

    /**
     * @param bool $isServiceStation
     */
    public function setIsServiceStation(bool $isServiceStation): void
    {
        $this->isServiceStation = $isServiceStation;
    }

    /**
     * @return bool
     */
    public function isNews(): bool
    {
        return $this->isNews;
    }

    /**
     * @param bool $isNews
     */
    public function setIsNews(bool $isNews): void
    {
        $this->isNews = $isNews;
    }
}