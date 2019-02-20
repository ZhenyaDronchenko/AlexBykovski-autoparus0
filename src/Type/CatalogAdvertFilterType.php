<?php

namespace App\Type;

use App\Entity\Brand;
use App\Entity\City;
use App\Entity\Model;
use App\Entity\SparePart;

class CatalogAdvertFilterType
{
    /**
     * @var Brand|null
     */
    private $brand;

    /**
     * @var Model|null
     */
    private $model;

    /**
     * @var SparePart|null
     */
    private $sparePart;

    /**
     * @var City|null
     */
    private $city;

    /**
     * @var boolean|null
     */
    private $inStock;

    /**
     * CatalogAdvertFilterType constructor.
     * @param Brand|null $brand
     * @param Model|null $model
     * @param SparePart|null $sparePart
     * @param City|null $city
     * @param bool|null $inStock
     */
    public function __construct(?Brand $brand, ?Model $model, ?SparePart $sparePart, ?City $city, ?bool $inStock)
    {
        $this->brand = $brand;
        $this->model = $model;
        $this->sparePart = $sparePart;
        $this->city = $city;
        $this->inStock = $inStock;
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
     * @return City|null
     */
    public function getCity(): ?City
    {
        return $this->city;
    }

    /**
     * @param City|null $city
     */
    public function setCity(?City $city): void
    {
        $this->city = $city;
    }

    /**
     * @return bool|null
     */
    public function getInStock(): ?bool
    {
        return $this->inStock;
    }

    /**
     * @param bool|null $inStock
     */
    public function setInStock(?bool $inStock): void
    {
        $this->inStock = $inStock;
    }
}