<?php

namespace App\Entity;

use App\Entity\Interfaces\VariableInterface;
use App\Handler\ResizeImageHandler;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BrandRepository")
 * @ORM\Table(name="brand")
 *
 * @ORM\HasLifecycleCallbacks()
 */
class Brand implements VariableInterface
{
    const ALL_BRANDS_NAME = "all_brands";
    const TOYOTA_RUS_URL = "toyota_rus";
    const TOYOTA_URL = "toyota";

    static $variables = [
        "[BRAND]" => "getName",
        "[URLBRAND]" => "getUrl",
        "[ENBRAND]" => "getBrandEn",
        "[RUBRAND]" => "getBrandRu",
        "[TEXTBRAND]" => "getText",
    ];

    /**
     * @var integer
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
    private $url;

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
    private $brandEn;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $brandRu;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $popular = 0;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $logo;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text")
     */
    private $text;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $active = 0;

    /**
     * @var Collection
     *
     * One Brand has Many Models.
     * @ORM\OneToMany(targetEntity="Model", mappedBy="brand")
     */
    private $models;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $urlConnectBamper;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $thumbnailLogo32;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $thumbnailLogo64;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $keyWords;

    /**
     * Brand constructor.
     */
    public function __construct()
    {
        $this->models = new ArrayCollection();
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
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     */
    public function setName(?string $name): void
    {
        $this->name = strtoupper($name);
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
        $this->url = strtolower($url);
    }

    /**
     * @return null|string
     */
    public function getBrandEn(): ?string
    {
        return $this->brandEn;
    }

    /**
     * @param null|string $brandEn
     */
    public function setBrandEn(?string $brandEn): void
    {
        $this->brandEn = $brandEn;
    }

    /**
     * @return null|string
     */
    public function getBrandRu(): ?string
    {
        return $this->brandRu;
    }

    /**
     * @param null|string $brandRu
     */
    public function setBrandRu(?string $brandRu): void
    {
        $this->brandRu = $brandRu;
    }

    /**
     * @return bool
     */
    public function isPopular(): bool
    {
        return $this->popular;
    }

    /**
     * @param bool $popular
     */
    public function setPopular(bool $popular): void
    {
        $this->popular = $popular;
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
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    /**
     * @return Collection
     */
    public function getModels(): Collection
    {
        return $this->models;
    }

    /**
     * @param Collection $models
     */
    public function setModels(Collection $models): void
    {
        $this->models = $models;
    }

    public function toSearchArray($isRussianText = false)
    {
        return [
            "label" => $isRussianText ? $this->brandRu : $this->name,
            "value" => $this->name,
            "url" => $this->url,
            "id" => $this->id,
            "isRussian" => $isRussianText,
        ];
    }

    public function replaceVariables($string)
    {
        foreach (self::$variables as $variable => $method){
            $string = str_replace($variable, $this->$method(), $string);
        }

        return $string;
    }

    public function __toString()
    {
        return (string)$this->id;
    }

    /**
     * @return null|string
     */
    public function getUrlConnectBamper(): ?string
    {
        return $this->urlConnectBamper ;
    }

    /**
     * @param null|string $urlConnectBamper
     */
    public function setUrlConnectBamper(?string $urlConnectBamper): void
    {
        $this->urlConnectBamper = $urlConnectBamper;
    }

    /**
     * @return null|string
     */
    public function getUrlConnectBamperIncludeBase(): ?string
    {
        return $this->urlConnectBamper ?: $this->url;
    }

    /**
     * @return null|string
     */
    public function getThumbnailLogo32(): ?string
    {
        return $this->thumbnailLogo32;
    }

    /**
     * @param null|string $thumbnailLogo32
     */
    public function setThumbnailLogo32(?string $thumbnailLogo32): void
    {
        $this->thumbnailLogo32 = $thumbnailLogo32;
    }

    /**
     * @return null|string
     */
    public function getThumbnailLogo64(): ?string
    {
        return $this->thumbnailLogo64;
    }

    /**
     * @param null|string $thumbnailLogo64
     */
    public function setThumbnailLogo64(?string $thumbnailLogo64): void
    {
        $this->thumbnailLogo64 = $thumbnailLogo64;
    }

    /**
     * @return null|string
     */
    public function getKeyWords(): ?string
    {
        return $this->keyWords;
    }

    /**
     * @param null|string $keyWords
     */
    public function setKeyWords(?string $keyWords): void
    {
        $this->keyWords = $keyWords;
    }

    public function addKeyWord($word)
    {
        $fullSame = $this->keyWords === $word;
        $inStart = ($pos = strpos($this->keyWords,  $word . '|')) !== false && $pos === 0;
        $inMiddle = strpos($this->keyWords,  '|' . $word . '|') !== false;
        $inEnd = ($pos = strpos($this->keyWords,  '|' . $word)) !== false && ($pos + (strlen('|' . $word) - 1)) === strlen($this->keyWords);

        if($fullSame || $inStart || $inMiddle || $inEnd){
            return false;
        }

        if($this->keyWords){
            $this->keyWords .= '|';
        }

        $this->keyWords .= $word;

        return true;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function createAndSetThumbnailLogo(LifecycleEventArgs $args)
    {
        $changeSet = $args->getEntityManager()->getUnitOfWork()->getEntityChangeSet($this);

        if(array_key_exists("logo", $changeSet) || !$this->id){
            $this->updateThumbnailLogos();
        }

        return true;
    }

    public function updateThumbnailLogos()
    {
        if(!$this->logo){
            $this->thumbnailLogo32 = null;
            $this->thumbnailLogo64 = null;

            return false;
        }

        $this->thumbnailLogo32 = ResizeImageHandler::resizeLogo($this, ResizeImageHandler::BRAND_WIDTH_32, ResizeImageHandler::BRAND_HEIGHT_32);
        $this->thumbnailLogo64 = ResizeImageHandler::resizeLogo($this, ResizeImageHandler::BRAND_WIDTH_64, ResizeImageHandler::BRAND_HEIGHT_64);
    }
}