<?php

namespace App\Type;


use App\Entity\Brand;
use App\Entity\Client\Client;
use App\Entity\Model;
use App\Entity\SparePart;

class AutoSparePartSpecificAdvertFilterType
{
    /**
     * @var Client
     */
    private $client;

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
     * @var integer|null
     */
    private $page;

    /**
     * AutoSparePartSpecificAdvertFilter constructor.
     * @param Client $client
     * @param Brand|null $brand
     * @param Model|null $model
     * @param SparePart|null $sparePart
     * @param int|null $page
     */
    public function __construct(Client $client, ?Brand $brand, ?Model $model, ?SparePart $sparePart, ?int $page)
    {
        $this->client = $client;
        $this->brand = $brand;
        $this->model = $model;
        $this->sparePart = $sparePart;
        $this->page = $page;
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @param Client $client
     */
    public function setClient(Client $client): void
    {
        $this->client = $client;
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
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage(int $page): void
    {
        $this->page = $page;
    }
}