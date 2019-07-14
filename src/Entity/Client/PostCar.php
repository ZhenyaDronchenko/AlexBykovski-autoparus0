<?php

namespace App\Entity\Client;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="post_car")
 */
class PostCar
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
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $brand;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $model;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $engineType;

    /**
     * @var Post
     *
     * Many PostCar have one Post. This is the owning side.
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="cars")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id")
     */
    private $post;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", options={"default" : true})
     */
    private $active = 1;

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
    public function getBrand(): string
    {
        return $this->brand;
    }

    /**
     * @param string $brand
     */
    public function setBrand(string $brand): void
    {
        $this->brand = $brand;
    }

    /**
     * @return null|string
     */
    public function getModel(): ?string
    {
        return $this->model;
    }

    /**
     * @param null|string $model
     */
    public function setModel(?string $model): void
    {
        $this->model = $model;
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
     * @return Post
     */
    public function getPost(): Post
    {
        return $this->post;
    }

    /**
     * @param Post $post
     */
    public function setPost(Post $post): void
    {
        $this->post = $post;
    }

    static function getPostCarByClientCar(UserCar $car)
    {
        $postCar = new PostCar();

        $brand = $car->getBrand() ? $car->getBrand()->getName() : null;
        $model = $car->getModel() ? $car->getModel()->getName() : null;
        $engineType = $car->getEngineType() ? $car->getEngineType()->getType() : null;

        $postCar->setBrand($brand);
        $postCar->setModel($model);
        $postCar->setEngineType($engineType);

        return $postCar;
    }

    public function toArray()
    {
        return [
            "id" => $this->getId(),
            "brand" => $this->getBrand(),
            "model" => $this->getModel(),
            "engineType" => $this->getEngineType(),
        ];
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }
}