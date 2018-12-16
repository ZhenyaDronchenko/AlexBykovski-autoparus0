<?php

namespace App\Entity;

use App\Entity\Interfaces\VariableInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CityRepository")
 * @ORM\Table(name="city")
 */
class City implements VariableInterface
{
    static $variables = [
        "[CITY]" => "getName",
        "[URLCITY]" => "getUrl",
        "[INCITY]" => "getPrepositional",
        "[TEXTCITY]" => "getText",
    ];

    const CAPITAL_TYPE = "CAPITAL";
    const REGIONAL_CITY_TYPE = "REGIONAL_CITY";
    const OTHERS_TYPE = "OTHERS";
    const ALL_CITIES = "all_cities";

    static $types = [
        "Столица" => self::CAPITAL_TYPE,
        "Областной город" => self::REGIONAL_CITY_TYPE,
        "другие" => self::OTHERS_TYPE
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
    private $name;

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
    private $prepositional;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $type;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $active = 0;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text")
     */
    private $text;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $logo;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $urlConnectBamper;

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
        $this->name = $name;
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
    public function getPrepositional(): ?string
    {
        return $this->prepositional;
    }

    /**
     * @param null|string $prepositional
     */
    public function setPrepositional(?string $prepositional): void
    {
        $this->prepositional = $prepositional;
    }

    /**
     * @return null|string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param null|string $type
     */
    public function setType(?string $type): void
    {
        $this->type = $type;
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
    public function getUrlConnectBamper(): ?string
    {
        return $this->urlConnectBamper;
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

    public function getTypeTranslate()
    {
        return array_flip(self::$types)[$this->type];
    }

    public function replaceVariables($string)
    {
        foreach (self::$variables as $variable => $method){
            $string = str_replace($variable, $this->$method(), $string);
        }

        return $string;
    }
}