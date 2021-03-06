<?php

namespace App\Entity\UniversalPage;


use App\Entity\Image;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

abstract class UniversalPage
{
    const UNIVERSAL_PAGE_BRAND = "brand";
    const UNIVERSAL_PAGE_CITY = "city";
    const UNIVERSAL_PAGE_SPARE_PART = "spare_part";
    /**
     * @var integer|null
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    protected $description;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    protected $headline1;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text")
     */
    protected $text1;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text")
     */
    protected $text2;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    protected $lastBreadCrumb;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $returnButtonLink;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $returnButtonText;

    protected $images;

    /**
     * UniversalPage constructor.
     */
    public function __construct()
    {
        $this->images = new ArrayCollection();
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
     * @return null|string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param null|string $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
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
    public function getHeadline1(): ?string
    {
        return $this->headline1;
    }

    /**
     * @param null|string $headline1
     */
    public function setHeadline1(?string $headline1): void
    {
        $this->headline1 = $headline1;
    }

    /**
     * @return null|string
     */
    public function getText1(): ?string
    {
        return $this->text1;
    }

    /**
     * @param null|string $text1
     */
    public function setText1(?string $text1): void
    {
        $this->text1 = $text1;
    }

    /**
     * @return null|string
     */
    public function getText2(): ?string
    {
        return $this->text2;
    }

    /**
     * @param null|string $text2
     */
    public function setText2(?string $text2): void
    {
        $this->text2 = $text2;
    }

    /**
     * @return null|string
     */
    public function getLastBreadCrumb(): ?string
    {
        return $this->lastBreadCrumb;
    }

    /**
     * @param null|string $lastBreadCrumb
     */
    public function setLastBreadCrumb(?string $lastBreadCrumb): void
    {
        $this->lastBreadCrumb = $lastBreadCrumb;
    }

    /**
     * @return null|string
     */
    public function getReturnButtonLink(): ?string
    {
        return $this->returnButtonLink;
    }

    /**
     * @param null|string $returnButtonLink
     */
    public function setReturnButtonLink(?string $returnButtonLink): void
    {
        $this->returnButtonLink = $returnButtonLink;
    }

    /**
     * @return null|string
     */
    public function getReturnButtonText(): ?string
    {
        return $this->returnButtonText;
    }

    /**
     * @param null|string $returnButtonText
     */
    public function setReturnButtonText(?string $returnButtonText): void
    {
        $this->returnButtonText = $returnButtonText;
    }

    /**
     * @return Collection
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    /**
     * @param Collection $images
     */
    public function setImages(Collection $images): void
    {
        $this->images = $images;
    }

    public function copy()
    {
        $newPage = $this->getNewObject();

        $newPage->setLastBreadCrumb($this->lastBreadCrumb);
        $newPage->setReturnButtonLink($this->returnButtonLink);
        $newPage->setReturnButtonText($this->returnButtonText);
        $newPage->setText2($this->text2);
        $newPage->setText1($this->text1);
        $newPage->setDescription($this->description);
        $newPage->setHeadline1($this->headline1);
        $newPage->setTitle($this->title);

        $images = new ArrayCollection();

        /** @var Image $image */
        foreach ($this->images as $image)
        {
            $geoLocation = $image->getGeoLocation() ?  $image->getGeoLocation()->copy() : null;
            $newImage = new Image($image->getImage());
            $newImage->setGeoLocation($geoLocation);

            $images->add($newImage);
        }

        $newPage->setImages($images);

        return $newPage;
    }

    private function getNewObject()
    {
        switch(get_class($this)){
            case UniversalPageBrand::class:
                return new UniversalPageBrand();
            case UniversalPageCity::class:
                return new UniversalPageCity();
            case UniversalPageSparePart::class:
                return new UniversalPageSparePart();
        }

        return null;
    }
}