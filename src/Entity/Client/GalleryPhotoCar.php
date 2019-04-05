<?php

namespace App\Entity\Client;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="gallery_photo_car")
 */
class GalleryPhotoCar
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
     * @ORM\Column(type="string")
     */
    private $model;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $engineType;

    /**
     * @var GalleryPhoto
     *
     * Many GalleryPhotoCar have one GalleryPhoto. This is the owning side.
     * @ORM\ManyToOne(targetEntity="App\Entity\Client\GalleryPhoto", inversedBy="cars")
     * @ORM\JoinColumn(name="gallery_photo_id", referencedColumnName="id")
     */
    private $galleryPhoto;

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
     * @return GalleryPhoto
     */
    public function getGalleryPhoto(): GalleryPhoto
    {
        return $this->galleryPhoto;
    }

    /**
     * @param GalleryPhoto $galleryPhoto
     */
    public function setGalleryPhoto(GalleryPhoto $galleryPhoto): void
    {
        $this->galleryPhoto = $galleryPhoto;
    }
}