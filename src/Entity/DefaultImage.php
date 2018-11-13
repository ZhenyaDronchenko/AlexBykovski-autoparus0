<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="default_image")
 */
class DefaultImage
{
    static $defaultImages = [
        "brand" => 1,
        "model" => 2,
        "site_logo_50*50" => 3,
        "site_logo_500*500" => 4,
        "user" => 5,
        "spare_part" => 10,
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
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $description;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $image;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $imagePath;

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
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param null|string $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return null|string
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param null|string $image
     */
    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    /**
     * @return null|string
     */
    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    /**
     * @param null|string $imagePath
     */
    public function setImagePath(?string $imagePath): void
    {
        $this->imagePath = $imagePath;
    }
}