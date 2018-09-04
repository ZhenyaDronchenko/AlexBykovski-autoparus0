<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="model")
 */
class Model
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
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $modelEn;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $modelRu;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $url;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $logo;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $isPopular = 0;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $text;

    /**
     * @var ModelTechnicalData
     *
     * One Model has One ModelTechnicalData.
     * @ORM\OneToOne(targetEntity="ModelTechnicalData", mappedBy="model")
     */
    private $technicalData;

    /**
     * @var Brand
     *
     * Many Models have One Brand.
     * @ORM\ManyToOne(targetEntity="Brand", inversedBy="models")
     * @ORM\JoinColumn(name="brand_id", referencedColumnName="id")
     */
    private $brand;

    /**
     * Model constructor.
     */
    public function __construct()
    {
        $this->technicalData = new ModelTechnicalData();
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
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return null|string
     */
    public function getModelEn(): ?string
    {
        return $this->modelEn;
    }

    /**
     * @param null|string $modelEn
     */
    public function setModelEn(?string $modelEn): void
    {
        $this->modelEn = $modelEn;
    }

    /**
     * @return null|string
     */
    public function getModelRu(): ?string
    {
        return $this->modelRu;
    }

    /**
     * @param null|string $modelRu
     */
    public function setModelRu(?string $modelRu): void
    {
        $this->modelRu = $modelRu;
    }

    /**
     * @return null|string
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param null|string $url
     */
    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return null|string
     */
    public function getLogo(): ?string
    {
        return $this->logo;
    }

    /**
     * @param null|string $logo
     */
    public function setLogo(?string $logo): void
    {
        $this->logo = $logo;
    }

    /**
     * @return bool
     */
    public function isPopular(): bool
    {
        return $this->isPopular;
    }

    /**
     * @param bool $isPopular
     */
    public function setIsPopular(bool $isPopular): void
    {
        $this->isPopular = $isPopular;
    }

    /**
     * @return null|string
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @param null|string $text
     */
    public function setText(?string $text): void
    {
        $this->text = $text;
    }

    /**
     * @return ModelTechnicalData
     */
    public function getTechnicalData(): ModelTechnicalData
    {
        return $this->technicalData;
    }

    /**
     * @param ModelTechnicalData $technicalData
     */
    public function setTechnicalData(ModelTechnicalData $technicalData): void
    {
        $this->technicalData = $technicalData;
    }

    /**
     * @return Brand
     */
    public function getBrand(): Brand
    {
        return $this->brand;
    }

    /**
     * @param Brand $brand
     */
    public function setBrand(Brand $brand): void
    {
        $this->brand = $brand;
    }
}