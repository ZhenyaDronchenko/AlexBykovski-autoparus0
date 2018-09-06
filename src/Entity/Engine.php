<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="engine")
 */
class Engine
{
    const PETROL_TYPE = "бензин";
    const DIESEL_TYPE = "дизель";
    const HYBRID_TYPE = "гибрид";
    const ELECTRIC_TYPE = "электро";

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
     * @ORM\Column(type="string")
     */
    private $type;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $capacity;

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
    public function getCapacity(): ?string
    {
        return $this->capacity;
    }

    /**
     * @param null|string $capacity
     */
    public function setCapacity(?string $capacity): void
    {
        $this->capacity = $capacity;
    }
}