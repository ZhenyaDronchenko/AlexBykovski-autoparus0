<?php

namespace App\Entity\Error;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Error\TypeOBD2ErrorRepository")
 * @ORM\Table(name="type_obd2error")
 */
class TypeOBD2Error
{
    const P_TYPE = "P";
    const B_TYPE = "B";
    const C_TYPE = "C";
    const U_TYPE = "U";
    const SECOND_BUTTON_P = "Коды ошибок работы двигателя OBD2";

    const TYPE_CATALOG_ORDER = [self::P_TYPE, self::B_TYPE, self::SECOND_BUTTON_P, self::C_TYPE, self::U_TYPE];

    static $variables = [
        "[TYPEOBD2]" => "getType",
        "[URLTYPEOBD2]" => "getUrl",
        "[LETTERTYPEOBD2]" => "getDesignation",
        "[TEXTTYPEOBD2]" => "getDescription",
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
    private $type;

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
    private $designation;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="CodeOBD2Error", mappedBy="type")
     */
    private $codes;

    /**
     * TypeOBD2Error constructor.
     */
    public function __construct()
    {
        $this->codes = new ArrayCollection();
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
    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    /**
     * @param null|string $designation
     */
    public function setDesignation(?string $designation): void
    {
        $this->designation = $designation;
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
     * @return Collection
     */
    public function getCodes(): Collection
    {
        return $this->codes;
    }

    /**
     * @param Collection $codes
     */
    public function setCodes(Collection $codes): void
    {
        $this->codes = $codes;
    }

    public function replaceVariables($string)
    {
        foreach (self::$variables as $variable => $method){
            $string = str_replace($variable, $this->$method(), $string);
        }

        return $string;
    }

    public function toArray()
    {
        return [
            "type" => $this->type,
            "url" => $this->url,
            "designation" => $this->designation,
        ];
    }
}