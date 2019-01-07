<?php

namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

abstract class InfoPageBase
{
    const CITY_LINKS = [
        "minsk" => "getMinskUrl",
        "brest" => "getBrestUrl",
        "vitebsk" => "getVitebskUrl",
        "grodno" => "getGrodnoUrl",
        "gomel" => "getGomelUrl",
        "mogilev" => "getMogilevUrl",
    ];

    /**
     * @var integer
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
     * @ORM\Column(type="string", nullable=true)
     */
    protected $returnButtonLink;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $returnButtonText;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $minskUrl;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $brestUrl;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $vitebskUrl;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $gomelUrl;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $grodnoUrl;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $mogilevUrl;

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
     * @return null|string
     */
    public function getMinskUrl(): ?string
    {
        return $this->minskUrl;
    }

    /**
     * @param null|string $minskUrl
     */
    public function setMinskUrl(?string $minskUrl): void
    {
        $this->minskUrl = $minskUrl;
    }

    /**
     * @return null|string
     */
    public function getBrestUrl(): ?string
    {
        return $this->brestUrl;
    }

    /**
     * @param null|string $brestUrl
     */
    public function setBrestUrl(?string $brestUrl): void
    {
        $this->brestUrl = $brestUrl;
    }

    /**
     * @return null|string
     */
    public function getVitebskUrl(): ?string
    {
        return $this->vitebskUrl;
    }

    /**
     * @param null|string $vitebskUrl
     */
    public function setVitebskUrl(?string $vitebskUrl): void
    {
        $this->vitebskUrl = $vitebskUrl;
    }

    /**
     * @return null|string
     */
    public function getGomelUrl(): ?string
    {
        return $this->gomelUrl;
    }

    /**
     * @param null|string $gomelUrl
     */
    public function setGomelUrl(?string $gomelUrl): void
    {
        $this->gomelUrl = $gomelUrl;
    }

    /**
     * @return null|string
     */
    public function getGrodnoUrl(): ?string
    {
        return $this->grodnoUrl;
    }

    /**
     * @param null|string $grodnoUrl
     */
    public function setGrodnoUrl(?string $grodnoUrl): void
    {
        $this->grodnoUrl = $grodnoUrl;
    }

    /**
     * @return null|string
     */
    public function getMogilevUrl(): ?string
    {
        return $this->mogilevUrl;
    }

    /**
     * @param null|string $mogilevUrl
     */
    public function setMogilevUrl(?string $mogilevUrl): void
    {
        $this->mogilevUrl = $mogilevUrl;
    }
}