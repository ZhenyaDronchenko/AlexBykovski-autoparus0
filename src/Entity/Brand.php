<?php

namespace App\Entity;

use App\Entity\Interfaces\VariableInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BrandRepository")
 * @ORM\Table(name="brand")
 */
class Brand implements VariableInterface
{
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

    public function toSearchArray()
    {
        return [
            "label" => $this->name,
            "value" => $this->name,
            "url" => $this->url,
        ];
    }

    public function replaceVariables($string)
    {
        foreach (self::$variables as $variable => $method){
            $string = str_replace($variable, $this->$method(), $string);
        }

        return $string;
    }
}