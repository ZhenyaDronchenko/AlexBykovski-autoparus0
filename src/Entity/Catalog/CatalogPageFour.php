<?php

namespace App\Entity\Catalog;

use Doctrine\ORM\Mapping as ORM;

abstract class CatalogPageFour
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    protected $description;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text")
     */
    protected $text1;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text")
     */
    protected $text2;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text")
     */
    protected $text3;

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
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param null|string $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
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
}