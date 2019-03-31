<?php

namespace App\Entity\Client;


use App\Entity\Image;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GalleryPhotoRepository")
 * @ORM\Table(name="gallery_photo")
 */
class GalleryPhoto
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
     * @var Image
     *
     * One GalleryPhoto has One Image.
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Image", cascade={"persist", "remove"})
     *
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id")
     */
    private $image;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var Gallery
     *
     * Many GalleryPhotos have One Gallery.
     * @ORM\ManyToOne(targetEntity="Gallery", inversedBy="photos")
     * @ORM\JoinColumn(name="gallery_id", referencedColumnName="id")
     */
    private $gallery;

    /**
     * GalleryPhoto constructor.
     * @param Image $image
     * @param Gallery $gallery
     */
    public function __construct(Image $image, Gallery $gallery)
    {
        $this->image = $image;
        $this->gallery = $gallery;
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
     * @return Image
     */
    public function getImage(): Image
    {
        return $this->image;
    }

    /**
     * @param Image $image
     */
    public function setImage(Image $image): void
    {
        $this->image = $image;
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
     * @return Gallery
     */
    public function getGallery(): Gallery
    {
        return $this->gallery;
    }

    /**
     * @param Gallery $gallery
     */
    public function setGallery(Gallery $gallery): void
    {
        $this->gallery = $gallery;
    }

    public function toArray()
    {
        return [
            "id" => $this->getId(),
            "address" => $this->getImage()->getGeoLocation()->getFullAddress(),
            "date" => $this->getImage()->getCreatedAt()->format("d.m.Y"),
            "time" => $this->getImage()->getCreatedAt()->format("H:i"),
            "path" => "/images/" . $this->getImage()->getImage(),
            "description" => $this->getDescription(),
        ];
    }
}