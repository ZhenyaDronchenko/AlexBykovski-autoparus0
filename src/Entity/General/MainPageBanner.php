<?php

namespace App\Entity\General;

use App\Entity\Image;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="main_page_banner")
 */
class MainPageBanner
{
    /**
     * @var integer|null
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $image;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $link;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $alt;

    /**
     * @var MainPage
     *
     * Many MainPageBanners have one MainPage. This is the owning side.
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\General\MainPage", inversedBy="banners")
     * @ORM\JoinColumn(name="main_page_id", referencedColumnName="id")
     */
    private $mainPage;

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
    public function getLink(): ?string
    {
        return $this->link;
    }

    /**
     * @param null|string $link
     */
    public function setLink(?string $link): void
    {
        $this->link = $link;
    }

    /**
     * @return null|string
     */
    public function getAlt(): ?string
    {
        return $this->alt;
    }

    /**
     * @param null|string $alt
     */
    public function setAlt(?string $alt): void
    {
        $this->alt = $alt;
    }

    /**
     * @return MainPage
     */
    public function getMainPage(): MainPage
    {
        return $this->mainPage;
    }

    /**
     * @param MainPage $mainPage
     */
    public function setMainPage(MainPage $mainPage): void
    {
        $this->mainPage = $mainPage;
    }
}