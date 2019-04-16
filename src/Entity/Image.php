<?php

namespace App\Entity;

use App\Upload\FileUpload;
use DateTime;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="image")
 *
 * @ORM\HasLifecycleCallbacks()
 */
class Image
{
    const USER_IMAGE_WIDTH = "320";
    const USER_IMAGE_HEIGHT = "320";

    const USER_THUMBNAIL_IMAGE_WIDTH = "92";
    const USER_THUMBNAIL_IMAGE_HEIGHT = "92";

    /**
     * @var integer|null
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
    private $image;

    /**
     * @var GeoLocation|null
     *
     * One Image has One GeoLocation.
     *
     * @ORM\OneToOne(targetEntity="GeoLocation", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="geo_location_id", referencedColumnName="id")
     */
    private $geoLocation;


    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * Image constructor.
     * @param string|null $image
     */
    public function __construct(string $image = null)
    {
        $this->image = $image;
        $this->createdAt = new DateTime();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    /**
     * @return GeoLocation|null
     */
    public function getGeoLocation(): ?GeoLocation
    {
        return $this->geoLocation;
    }

    /**
     * @param GeoLocation|null $geoLocation
     */
    public function setGeoLocation(?GeoLocation $geoLocation): void
    {
        $this->geoLocation = $geoLocation;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function prePersistOrPreUpdate(LifecycleEventArgs $args)
    {
        $changeSet = $args->getEntityManager()->getUnitOfWork()->getEntityChangeSet($this);

        if(array_key_exists("image", $changeSet) || !$this->id){
            $this->convertImage();
        }

        return true;
    }

    private function convertImage()
    {
        if(!$this->getImage()){
            return false;
        }

        $fileInfo = pathinfo($this->getImage());

        if(!isset($fileInfo['extension']) || !$fileInfo['extension'] || $fileInfo['extension'] === "webp"){
            return false;
        }

        $this->setImage(FileUpload::fileToWebP($this->getImage()));

        return true;
    }
}