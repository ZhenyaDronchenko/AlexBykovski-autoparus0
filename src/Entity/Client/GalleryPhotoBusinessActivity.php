<?php

namespace App\Entity\Client;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="gallery_photo_business_activity")
 */
class GalleryPhotoBusinessActivity
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
    private $city;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $activity;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $companyName;

    /**
     * @var GalleryPhoto
     *
     * Many GalleryPhotoCar have one GalleryPhoto. This is the owning side.
     * @ORM\ManyToOne(targetEntity="App\Entity\Client\GalleryPhoto", inversedBy="businessActivities")
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
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(string $city): void
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
     * @return null|string
     */
    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    /**
     * @param null|string $companyName
     */
    public function setCompanyName(?string $companyName): void
    {
        $this->companyName = $companyName;
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

    static function getGalleryActivityByClientActivity(GalleryPhoto $galleryPhoto, $activity)
    {
        $galleryActivity = new GalleryPhotoBusinessActivity();
        $galleryActivity->setGalleryPhoto($galleryPhoto);

        $city = $galleryPhoto->getGallery()->getClient()->getSellerData()->getSellerCompany()->getCity();
        $companyName = $galleryPhoto->getGallery()->getClient()->getSellerData()->getSellerCompany()->getCompanyName();

        $galleryActivity->setCity($city);
        $galleryActivity->setActivity($activity);
        $galleryActivity->setCompanyName($companyName);

        return $galleryActivity;
    }

    public function toArray()
    {
        return [
            "id" => $this->getId(),
            "city" => $this->getCity(),
            "activity" => SellerCompany::$activities[$this->getActivity()],
            "companyName" => $this->getCompanyName(),
        ];
    }
}