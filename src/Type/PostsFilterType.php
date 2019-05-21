<?php

namespace App\Type;


use App\Entity\Brand;
use App\Entity\City;
use App\Entity\Client\Client;
use App\Entity\Model;

class PostsFilterType
{
    const ADMINS = [34, 29, 28, 27, 26, 24, 46, 47, 48, 50, 56];

    /** @var Client|array */
    private $users;

    /** @var Brand|null */
    private $brand;

    /** @var Model|null */
    private $model;

    /** @var City|null */
    private $city;

    /** @var string|null */
    private $activity;

    /** @var integer|null */
    private $limit;

    /** @var integer|null */
    private $offset;

    /**
     * PostsFilterType constructor.
     * @param Client|array $users
     * @param Brand|null $brand
     * @param Model|null $model
     * @param City|null $city
     * @param null|string $activity
     * @param null|integer limit
     * @param null|integer offset
     */
    public function __construct(
        $users,
        ?Brand $brand,
        ?Model $model,
        ?City $city,
        ?string $activity,
        ?int $limit = null,
        ?int $offset = null
    )
    {
        $this->users = $users;
        $this->brand = $brand;
        $this->model = $model;
        $this->city = $city;
        $this->activity = $activity;
        $this->limit = $limit;
        $this->offset = $offset;
    }

    /**
     * @return Client|array
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param Client|array $users
     */
    public function setUsers($users): void
    {
        $this->users = $users;
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
     * @return null|string
     */
    public function getActivity(): ?string
    {
        return $this->activity;
    }

    /**
     * @param null|string $activity
     */
    public function setActivity(?string $activity): void
    {
        $this->activity = $activity;
    }

    /**
     * @return int|null
     */
    public function getLimit(): ?int
    {
        return $this->limit;
    }

    /**
     * @param int|null $limit
     */
    public function setLimit(?int $limit): void
    {
        $this->limit = $limit;
    }

    /**
     * @return int|null
     */
    public function getOffset(): ?int
    {
        return $this->offset;
    }

    /**
     * @param int|null $offset
     */
    public function setOffset(?int $offset): void
    {
        $this->offset = $offset;
    }
}