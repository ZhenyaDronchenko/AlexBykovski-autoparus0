<?php

namespace App\Entity\Phone;

use App\Entity\Interfaces\VariableInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhoneSparePartRepository")
 * @ORM\Table(name="phone_spare_part")
 */
class PhoneSparePart implements VariableInterface
{
    static $variables = [
        "[TELZAP]" => "getName",
        "[URLTELZAP]" => "getUrl",
        "[VINTELZAP]" => "getNameAccusative",
        "[RODTELZAP]" => "getNameGenitive",
        "[TELZAP1]" => "getAlternativeName1",
        "[TELZAP2]" => "getAlternativeName2",
        "[TELMALFUNCTION1]" => "getMalfunction1",
        "[TELMALFUNCTION2]" => "getMalfunction2",
        "[TEXTTELZAP1]" => "getText1",
        "[TEXTTELZAP2]" => "getText2",
        "[TEXTTELZAP3]" => "getText3",
        "[TELZAPRAB]" => "getWork",
        "[TELZAPRABSDEL]" => "getActionWork",

    ];

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
    private $nameGenitive;

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
     * @var boolean
     *
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $active = 1;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $malfunction1;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $malfunction2;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $text1;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $text2;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $text3;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $work;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $actionWork;

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
    public function getMalfunction1(): ?string
    {
        return $this->malfunction1;
    }

    /**
     * @param null|string $malfunction1
     */
    public function setMalfunction1(?string $malfunction1): void
    {
        $this->malfunction1 = $malfunction1;
    }

    /**
     * @return null|string
     */
    public function getMalfunction2(): ?string
    {
        return $this->malfunction2;
    }

    /**
     * @param null|string $malfunction2
     */
    public function setMalfunction2(?string $malfunction2): void
    {
        $this->malfunction2 = $malfunction2;
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
    public function getText3(): ?string
    {
        return $this->text3;
    }

    /**
     * @param null|string $text3
     */
    public function setText3(?string $text3): void
    {
        $this->text3 = $text3;
    }

    /**
     * @return null|string
     */
    public function getWork(): ?string
    {
        return $this->work;
    }

    /**
     * @param null|string $work
     */
    public function setWork(?string $work): void
    {
        $this->work = $work;
    }

    /**
     * @return null|string
     */
    public function getActionWork(): ?string
    {
        return $this->actionWork;
    }

    /**
     * @param null|string $actionWork
     */
    public function setActionWork(?string $actionWork): void
    {
        $this->actionWork = $actionWork;
    }

    public function toSearchArray()
    {
        return [
            "label" => $this->name,
            "value" => $this->name,
            "url" => $this->url,
        ];
    }

    public function toWorkSearchArray()
    {
        return [
            "label" => $this->work,
            "value" => $this->work,
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