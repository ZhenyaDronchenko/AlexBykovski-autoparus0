<?php

namespace App\Entity\Client;

use App\Entity\Image;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 * @ORM\Table(name="post")
 */
class Post
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
     * @var Collection
     *
     * One Post has many PostPhotos. This is the inverse side.
     * @ORM\OneToMany(targetEntity="PostPhoto", mappedBy="post", cascade={"persist", "remove"})
     */
    private $postPhotos;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var Collection
     *
     * One Post has many PostCars. This is the inverse side.
     * @ORM\OneToMany(targetEntity="PostCar", mappedBy="post", cascade={"persist", "remove"})
     */
    private $cars;

    /**
     * @var Collection
     *
     * One Post has many PostBusinessActivities. This is the inverse side.
     * @ORM\OneToMany(targetEntity="PostBusinessActivity", mappedBy="post", cascade={"persist", "remove"})
     */
    private $businessActivities;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $type;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $headline;

    /**
     * @var Client
     *
     * Many Posts have one Client. This is the owning side.
     *
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="posts")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    private $client;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * Post constructor.
     *
     * @param Client $client
     * @param Image $image
     * @param string $type
     */
    public function __construct(Client $client, Image $image, string $type = self::SIMPLE_TYPE)
    {
        $this->type = $type;
        $this->cars = new ArrayCollection();
        $this->businessActivities = new ArrayCollection();
        $this->postPhotos = new ArrayCollection();
        $this->client = $client;
        $this->createdAt = new DateTime();

        $this->setInitPostPhoto($image);

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

    public function setInitPostPhoto(Image $image)
    {
        $postPhoto = new PostPhoto($image, $this);

        $this->postPhotos->add($postPhoto);
    }

    /**
     * @return Collection
     */
    public function getPostPhotos(): Collection
    {
        return $this->postPhotos;
    }

    /**
     * @param Collection $postPhotos
     */
    public function setPostPhotos(Collection $postPhotos): void
    {
        $this->postPhotos = $postPhotos;
    }

    /**
     * @return null|string
     */
    public function getHeadline(): ?string
    {
        return $this->headline;
    }

    /**
     * @param null|string $headline
     */
    public function setHeadline(?string $headline): void
    {
        $this->headline = $headline;
    }

    public function toArray()
    {
        /** @var Image $firstImage */
        $firstImage = $this->getPostPhotos()->first()->getImage();

        return [
            "id" => $this->id,
            "address" => $firstImage->getGeoLocation()->getFullAddress(),
            "date" => $this->createdAt->format("d.m.Y"),
            "time" => $this->createdAt->format("H:i"),
            "pathImage" => "/images/" . $firstImage->getImage(),
            "description" => $this->getDescription(),
            "cars" => $this->getCarsArray(),
            "businessActivities" => $this->getBusinessActivitiesArray(),
            "type" => $this->getType(),
        ];
    }

    public function toSearchArray()
    {
        $user = $this->getClient();
        $userPhoto = $user->getThumbnailPhoto() ?: null;
        /** @var Image $firstImage */
        $firstImage = $this->getPostPhotos()->first()->getImage();
        $geoLocation = $firstImage->getGeoLocation();
        $address = $geoLocation->getCountry();
        $address .= $geoLocation->getCity() ? ", " . $geoLocation->getCity() : "";

        return [
            "id" => $this->getId(),
            "userPhoto" => $userPhoto ? "/images/" . $userPhoto->getImage() : "",
            "userName" => $user->getName(),
            "image" => $firstImage->getImage() ? "/images/" . $firstImage->getImage() : "",
            "description" => str_replace("\n", "<br>", $this->getDescription()),
            "address" => $address,
            "date" => $this->createdAt->format("d.m.Y"),
            "time" => $this->createdAt->format("H:i"),
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
        $cars = $this->getClient()->getCars();

        $postCars = new ArrayCollection();

        /** @var UserCar $car */
        foreach ($cars as $car){
            $postCar = PostCar::getPostCarByClientCar($car);
            $postCar->setPost($this);

            $postCars->add($postCar);
        }

        $this->setCars($postCars);
    }

    public function setUserBusinessActivities()
    {
        $company = $this->getClient()->getSellerData() ?
            $this->getClient()->getSellerData()->getSellerCompany() : null;

        if(!$company || !$company->getCity()){
            return false;
        }

        $businessActivities = new ArrayCollection();

        if($company->isSparePartSeller()){
            $postBusinessActivity = PostBusinessActivity::getPostActivityByClientActivity($this, SellerCompany::ACTIVITY_URL_SPARE_PART_SELLER);
            $businessActivities->add($postBusinessActivity);
        }

        if($company->isAutoSeller()){
            $postBusinessActivity = PostBusinessActivity::getPostActivityByClientActivity($this, SellerCompany::ACTIVITY_URL_AUTO_SELLER);
            $businessActivities->add($postBusinessActivity);
        }

        if($company->isService()){
            $postBusinessActivity = PostBusinessActivity::getPostActivityByClientActivity($this, SellerCompany::ACTIVITY_URL_SERVICE);
            $businessActivities->add($postBusinessActivity);
        }

        if($company->isNews()){
            $postBusinessActivity = PostBusinessActivity::getPostActivityByClientActivity($this, SellerCompany::ACTIVITY_URL_NEWS);
            $businessActivities->add($postBusinessActivity);
        }

        if($company->isTourism()){
            $postBusinessActivity = PostBusinessActivity::getPostActivityByClientActivity($this, SellerCompany::ACTIVITY_URL_TOURISM);
            $businessActivities->add($postBusinessActivity);
        }

        $this->setBusinessActivities($businessActivities);
    }

    public function getCarsArray()
    {
        $cars = [];

        /** @var PostCar $car */
        foreach ($this->getCars() as $car){
            $cars[] = $car->toArray();
        }

        return $cars;
    }

    public function getBusinessActivitiesArray()
    {
        $businessActivities = [];

        /** @var PostBusinessActivity $activity */
        foreach ($this->businessActivities as $activity){
            $businessActivities[] = $activity->toArray();
        }

        return $businessActivities;
    }

    /** PostPhotoCar|PostPhotoBusinessActivity|boolean */
    public function getFilter($id)
    {
        if($this->type === self::SIMPLE_TYPE){
            /** @var PostCar $car */
            foreach ($this->cars as $car){
                if($car->getId() == $id){
                    return $car;
                }
            }
        }
        else{
            /** @var PostBusinessActivity $activity */
            foreach ($this->businessActivities as $activity){
                if($activity->getId() == $id){
                    return $activity;
                }
            }
        }

        return false;
    }
}