<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EngineTypeRepository")
 * @ORM\Table(name="engine_type")
 */
class EngineType
{
    static $variables = [
        "[ENGINE_TYPE]" => "getType",
    ];

    const PETROL_NAME = "Бензиновый";
    const DIESEL_NAME = "Дизельный";
    const HYBRID_NAME = "Гибридный";
    const ELECTRIC_NAME = "Электродвигатель";

    const ELECTRIC_URL = "electric";

    const TYPE_NAMES = [
        "petrol" => self::PETROL_NAME,
        "diesel" => self::DIESEL_NAME,
        "hybrid" => self::HYBRID_NAME,
        "electric" => self::ELECTRIC_NAME,
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
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $url;

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
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function __toString()
    {
        return (string)$this->id;
    }

    public function replaceVariables($string)
    {
        foreach (self::$variables as $variable => $method){
            $string = str_replace($variable, $this->$method(), $string);
        }

        return $string;
    }
}