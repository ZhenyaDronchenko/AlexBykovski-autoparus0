<?php

namespace App\Type;


use App\Entity\Brand;
use App\Entity\City;
use App\Entity\Client\Client;
use App\Entity\Model;

class PostsFilterType
{
    //const ADMINS = [34, 29, 28, 27, 26, 24, 46, 47, 48, 50, 56];
    const USERS_ACCESS_POST_HOMEPAGE = "USERS_ACCESS_POST_HOMEPAGE";

    /** @var Client|string */
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

    /** @var string|null */
    private $notRole;

    /**
     * PostsFilterType constructor.
     * @param Client|string $users
     * @param Brand|null $brand
     * @param Model|null $model
     * @param City|null $city
     * @param null|string $activity
     * @param null|integer $limit
     * @param null|integer $offset
     * @param null|string $notRole
     * @param null|string $type
     */
    public function __construct(
        $users,
        ?Brand $brand,
        ?Model $model,
        ?City $city,
        ?string $activity,
        ?int $limit = null,
        ?int $offset = null,
        ?string $notRole = null,
        ?string $type = null
    )
    {
        $this->users = $users;
        $this->brand = $brand;
        $this->model = $model;
        $this->city = $city;
        $this->activity = $activity;
        $this->limit = $limit;
        $this->offset = $offset;
        $this->notRole = $notRole;
        $this->type = $type;
    }

    /**
     * @return Client|string
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param Client|string $users
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

    /**
     * @return null|string
     */
    public function getNotRole(): ?string
    {
        return $this->notRole;
    }

    /**
     * @param null|string $notRole
     */
    public function setNotRole(?string $notRole): void
    {
        $this->notRole = $notRole;
    }

    /**
     * @return null|string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param null|string $type
     */
    public function setType(?string $type): void
    {
        $this->type = $type;
    }
}