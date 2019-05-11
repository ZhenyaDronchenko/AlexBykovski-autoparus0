<?php

namespace App\Entity\Client;

use App\Entity\Image;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GalleryPhotoRepository")
 * @ORM\Table(name="gallery_photo")
 */
class GalleryPhoto
{
    const SIMPLE_TYPE = "simple";
    const BUSINESS_TYPE = "business";

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
     * @var Collection
     *
     * One GalleryPhoto has many GalleryPhotoCars. This is the inverse side.
     * @ORM\OneToMany(targetEntity="App\Entity\Client\GalleryPhotoCar", mappedBy="galleryPhoto", cascade={"persist", "remove"})
     */
    private $cars;

    /**
     * @var Collection
     *
     * One GalleryPhoto has many GalleryPhotoBusinessActivities. This is the inverse side.
     * @ORM\OneToMany(targetEntity="App\Entity\Client\GalleryPhotoBusinessActivity", mappedBy="galleryPhoto", cascade={"persist", "remove"})
     */
    private $businessActivities;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $type;

    /**
     * GalleryPhoto constructor.
     * @param Image $image
     * @param Gallery $gallery
     * @param string $type
     */
    public function __construct(Image $image, Gallery $gallery, string $type = self::SIMPLE_TYPE)
    {
        $this->image = $image;
        $this->gallery = $gallery;
        $this->type = $type;
        $this->cars = new ArrayCollection();
        $this->businessActivities = new ArrayCollection();

        if($type == self::SIMPLE_TYPE){
            $this->setUserCars();
        }
        else {
            $this->setUserBusinessActivities();
        }
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
     * @return Collection
     */
    public function getBusinessActivities(): Collection
    {
        return $this->businessActivities;
    }

    /**
     * @param Collection $businessActivities
     */
    public function setBusinessActivities(Collection $businessActivities): void
    {
        $this->businessActivities = $businessActivities;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
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
            "cars" => $this->getCarsArray(),
            "businessActivities" => $this->getBusinessActivitiesArray(),
            "type" => $this->getType(),
        ];
    }

    public function toSearchArray()
    {
        $user = $this->getGallery()->getClient();
        $userPhoto = $user->getThumbnailPhoto() ?: null;
        $geoLocation = $this->getImage()->getGeoLocation();
        $address = $geoLocation->getCountry();
        $address .= $geoLocation->getCity() ? ", " . $geoLocation->getCity() : "";

        return [
            "id" => $this->getId(),
            "userPhoto" => $userPhoto ? "/images/" . $userPhoto->getImage() : "",
            "userName" => $user->getName(),
            "image" => $this->getImage()->getImage() ? "/images/" . $this->getImage()->getImage() : "",
            "description" => str_replace("\n", "<br>", $this->getDescription()),
            "address" => $address,
            "date" => $this->getImage()->getCreatedAt()->format("d.m.Y"),
            "time" => $this->getImage()->getCreatedAt()->format("H:i"),
            "type" => $this->type,
            "userId" => $user->getId(),
            "city" => $this->type === self::BUSINESS_TYPE && $this->businessActivities->count() ?
                $this->businessActivities->first()->getCity() : null,
            "activity" => $this->type === self::BUSINESS_TYPE && $this->businessActivities->count() ?
                $this->businessActivities->first()->getActivity() : null,
            "brand" => $this->type === self::SIMPLE_TYPE && $this->cars->count() ?
                $this->cars->first()->getBrand() : null,
            "model" => $this->type === self::SIMPLE_TYPE && $this->cars->count() ?
                $this->cars->first()->getModel() : null,
        ];
    }

    public function setUserCars()
    {
        $cars = $this->getGallery()->getClient()->getCars();

        $galleryCars = new ArrayCollection();

        /** @var UserCar $car */
        foreach ($cars as $car){
            $galleryCar = GalleryPhotoCar::getGalleryCarByClientCar($car);
            $galleryCar->setGalleryPhoto($this);

            $galleryCars->add($galleryCar);
        }

        $this->setCars($galleryCars);
    }

    public function setUserBusinessActivities()
    {
        $company = $this->getGallery()->getClient()->getSellerData() ?
            $this->getGallery()->getClient()->getSellerData()->getSellerCompany() : null;

        if(!$company || !$company->getCity()){
            return false;
        }

        $businessActivities = new ArrayCollection();

        if($company->isSparePartSeller()){
            $galleryBusinessActivity = GalleryPhotoBusinessActivity::getGalleryActivityByClientActivity($this, SellerCompany::ACTIVITY_URL_SPARE_PART_SELLER);
            $businessActivities->add($galleryBusinessActivity);
        }

        if($company->isAutoSeller()){
            $galleryBusinessActivity = GalleryPhotoBusinessActivity::getGalleryActivityByClientActivity($this, SellerCompany::ACTIVITY_URL_AUTO_SELLER);
            $businessActivities->add($galleryBusinessActivity);
        }

        if($company->isService()){
            $galleryBusinessActivity = GalleryPhotoBusinessActivity::getGalleryActivityByClientActivity($this, SellerCompany::ACTIVITY_URL_SERVICE);
            $businessActivities->add($galleryBusinessActivity);
        }

        if($company->isNews()){
            $galleryBusinessActivity = GalleryPhotoBusinessActivity::getGalleryActivityByClientActivity($this, SellerCompany::ACTIVITY_URL_NEWS);
            $businessActivities->add($galleryBusinessActivity);
        }

        if($company->isTourism()){
            $galleryBusinessActivity = GalleryPhotoBusinessActivity::getGalleryActivityByClientActivity($this, SellerCompany::ACTIVITY_URL_TOURISM);
            $businessActivities->add($galleryBusinessActivity);
        }

        $this->setBusinessActivities($businessActivities);
    }

    public function getCarsArray()
    {
        $cars = [];

        /** @var GalleryPhotoCar $car */
        foreach ($this->getCars() as $car){
            $cars[] = $car->toArray();
        }

        return $cars;
    }

    public function getBusinessActivitiesArray()
    {
        $businessActivities = [];

        /** @var GalleryPhotoBusinessActivity $activity */
        foreach ($this->businessActivities as $activity){
            $businessActivities[] = $activity->toArray();
        }

        return $businessActivities;
    }

    /** GalleryPhotoCar|GalleryPhotoBusinessActivity|boolean */
    public function getFilter($id)
    {
        if($this->type === self::SIMPLE_TYPE){
            /** @var GalleryPhotoCar $car */
            foreach ($this->cars as $car){
                if($car->getId() == $id){
                    return $car;
                }
            }
        }
        else{
            /** @var GalleryPhotoBusinessActivity $activity */
            foreach ($this->businessActivities as $activity){
                if($activity->getId() == $id){
                    return $activity;
                }
            }
        }

        return false;
    }
}