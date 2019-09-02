<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="spare_part_condition")
 */
class SparePartCondition
{
    const USED_DESCRIPTION = "БУ";
    const NEW_DESCRIPTION = "Новая";
    const REBUILT_DESCRIPTION = "Восстановленная";

    const USED_CONDITION = "БУ запчасть";
    const NEW_CONDITION = "Новая запчасть";
    const REBUILT_CONDITION = "Восстановленная запчасть";

    const SINGLE_USED_ADJECTIVE = "БУ";
    const SINGLE_NEW_ADJECTIVE = "Новый";
    const SINGLE_REBUILT_ADJECTIVE = "Восстановленный";

    const PLURAL_USED_ADJECTIVE = "Бывшие в употреблении";
    const PLURAL_NEW_ADJECTIVE = "Новые";
    const PLURAL_REBUILT_ADJECTIVE = "Восстановленные";

    static $conditions = [
        "used" => "БУ",
        "new" => "Новая",
        "rebuilt" => "Восстановленная",
    ];

    static $variables = [
        "[ZAP_CONDITION]" => "getSpCondition",
        "[SINGLE_ZAP_CONDITION]" => "getSingleAdjective",
        "[PLURAL_ZAP_CONDITION]" => "getPluralAdjective",
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
     * @ORM\Column(type="string", nullable=true)
     */
    private $description;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $spCondition;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $singleAdjective;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $pluralAdjective;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", options={"default" : true})
     */
    private $isActive = 1;

    /**
     * @var SparePart
     *
     * Many SparePartConditions have One SparePart.
     * @ORM\ManyToOne(targetEntity="SparePart", inversedBy="conditions")
     * @ORM\JoinColumn(name="spare_part_id", referencedColumnName="id")
     */
    private $sparePart;

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
    public function getSpCondition(): ?string
    {
        return $this->spCondition;
    }

    /**
     * @param null|string $spCondition
     */
    public function setSpCondition(?string $spCondition): void
    {
        $this->spCondition = $spCondition;
    }

    /**
     * @return null|string
     */
    public function getSingleAdjective(): ?string
    {
        return $this->singleAdjective;
    }

    /**
     * @param null|string $singleAdjective
     */
    public function setSingleAdjective(?string $singleAdjective): void
    {
        $this->singleAdjective = $singleAdjective;
    }

    /**
     * @return null|string
     */
    public function getPluralAdjective(): ?string
    {
        return $this->pluralAdjective;
    }

    /**
     * @param null|string $pluralAdjective
     */
    public function setPluralAdjective(?string $pluralAdjective): void
    {
        $this->pluralAdjective = $pluralAdjective;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     */
    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    /**
     * @return SparePart
     */
    public function getSparePart(): SparePart
    {
        return $this->sparePart;
    }

    /**
     * @param SparePart $sparePart
     */
    public function setSparePart(SparePart $sparePart): void
    {
        $this->sparePart = $sparePart;
    }

    public function setConditionDetails(?string $description, ?string $condition, ?string $singleAdjective, ?string $pluralAdjective, SparePart $sparePart, $isActive = 1) : void
    {
        $this->description = $description;
        $this->spCondition = $condition;
        $this->singleAdjective = $singleAdjective;
        $this->pluralAdjective = $pluralAdjective;
        $this->sparePart = $sparePart;
        $this->isActive = $isActive;
    }

    public function replaceVariables($string)
    {
        foreach (self::$variables as $variable => $method){
            $string = str_replace($variable, $this->$method(), $string);
        }

        return $string;
    }
}