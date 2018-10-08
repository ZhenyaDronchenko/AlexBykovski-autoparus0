<?php

namespace App\Entity\Client;

use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="client")
 */
class Client extends User
{
    /**
     * @var BuyerData
     *
     * One Client has One BuyerData.
     * @ORM\OneToOne(targetEntity="BuyerData", mappedBy="client", cascade={"persist", "remove"})
     */
    private $buyerData;

    /**
     * @var SellerData|null
     *
     * One Client has One SellerData.
     * @ORM\OneToOne(targetEntity="SellerData", mappedBy="client", cascade={"persist", "remove"})
     */
    private $sellerData;

    /**
     * @var Collection
     *
     * One User has Many UserCars.
     * @ORM\OneToMany(targetEntity="UserCar", mappedBy="client", cascade={"persist"})
     */
    private $cars;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $city;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $country;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $isHelper = 0;

    public function __construct()
    {
        parent::__construct();

        $this->addRole(User::ROLE_CLIENT);
        $this->cars = new ArrayCollection();
        $this->buyerData = new BuyerData($this);
    }

    /**
     * @return BuyerData
     */
    public function getBuyerData(): BuyerData
    {
        return $this->buyerData;
    }

    /**
     * @param BuyerData $buyerData
     */
    public function setBuyerData(BuyerData $buyerData): void
    {
        $this->buyerData = $buyerData;
    }

    /**
     * @return SellerData|null
     */
    public function getSellerData(): ?SellerData
    {
        return $this->sellerData;
    }

    /**
     * @param SellerData|null $sellerData
     */
    public function setSellerData(?SellerData $sellerData): void
    {
        $this->sellerData = $sellerData;
    }

    /**
     * @return Collection
     */
    public function getCars(): Collection
    {
        return $this->cars;
    }

    /**
     * @param Collection $cars
     */
    public function setCars(Collection $cars): void
    {
        $this->cars = $cars;
    }

    /**
     * @return null|string
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param null|string $city
     */
    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return null|string
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param null|string $country
     */
    public function setCountry(?string $country): void
    {
        $this->country = $country;
    }

    /**
     * @return bool
     */
    public function isHelper(): bool
    {
        return $this->isHelper;
    }

    /**
     * @param bool $isHelper
     */
    public function setIsHelper(bool $isHelper): void
    {
        $this->isHelper = $isHelper;
    }

    public function addCar(UserCar $car)
    {
        if (!$this->cars->contains($car)) {
            $this->cars->add($car);

            $car->setClient($this);
        }
    }

    public function removeCar(UserCar $car)
    {
        $this->cars->removeElement($cag);
    }
}