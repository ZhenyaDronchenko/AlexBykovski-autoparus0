<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="spare_part")
 */
class SparePart
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
    private $url;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $nameAccusative;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $nameInstrumental;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $nameGenitive;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $namePlural;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $alternativeName1;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $alternativeName2;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $alternativeName3;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $alternativeName4;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $alternativeName5;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", options={"default" : true})
     */
    private $popular = 1;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $logo;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $text;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $active = 1;

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
    public function getNameAccusative(): ?string
    {
        return $this->nameAccusative;
    }

    /**
     * @param null|string $nameAccusative
     */
    public function setNameAccusative(?string $nameAccusative): void
    {
        $this->nameAccusative = $nameAccusative;
    }

    /**
     * @return null|string
     */
    public function getNameInstrumental(): ?string
    {
        return $this->nameInstrumental;
    }

    /**
     * @param null|string $nameInstrumental
     */
    public function setNameInstrumental(?string $nameInstrumental): void
    {
        $this->nameInstrumental = $nameInstrumental;
    }

    /**
     * @return null|string
     */
    public function getNameGenitive(): ?string
    {
        return $this->nameGenitive;
    }

    /**
     * @param null|string $nameGenitive
     */
    public function setNameGenitive(?string $nameGenitive): void
    {
        $this->nameGenitive = $nameGenitive;
    }

    /**
     * @return null|string
     */
    public function getNamePlural(): ?string
    {
        return $this->namePlural;
    }

    /**
     * @param null|string $namePlural
     */
    public function setNamePlural(?string $namePlural): void
    {
        $this->namePlural = $namePlural;
    }

    /**
     * @return null|string
     */
    public function getAlternativeName1(): ?string
    {
        return $this->alternativeName1;
    }

    /**
     * @param null|string $alternativeName1
     */
    public function setAlternativeName1(?string $alternativeName1): void
    {
        $this->alternativeName1 = $alternativeName1;
    }

    /**
     * @return null|string
     */
    public function getAlternativeName2(): ?string
    {
        return $this->alternativeName2;
    }

    /**
     * @param null|string $alternativeName2
     */
    public function setAlternativeName2(?string $alternativeName2): void
    {
        $this->alternativeName2 = $alternativeName2;
    }

    /**
     * @return null|string
     */
    public function getAlternativeName3(): ?string
    {
        return $this->alternativeName3;
    }

    /**
     * @param null|string $alternativeName3
     */
    public function setAlternativeName3(?string $alternativeName3): void
    {
        $this->alternativeName3 = $alternativeName3;
    }

    /**
     * @return null|string
     */
    public function getAlternativeName4(): ?string
    {
        return $this->alternativeName4;
    }

    /**
     * @param null|string $alternativeName4
     */
    public function setAlternativeName4(?string $alternativeName4): void
    {
        $this->alternativeName4 = $alternativeName4;
    }

    /**
     * @return null|string
     */
    public function getAlternativeName5(): ?string
    {
        return $this->alternativeName5;
    }

    /**
     * @param null|string $alternativeName5
     */
    public function setAlternativeName5(?string $alternativeName5): void
    {
        $this->alternativeName5 = $alternativeName5;
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
}