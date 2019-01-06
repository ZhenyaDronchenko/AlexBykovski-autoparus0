<?php

namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="not_found_page")
 */
class NotFoundPage
{
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
    private $title;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $description;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $middleText;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $middleLink;

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
    public function getMiddleText(): ?string
    {
        return $this->middleText;
    }

    /**
     * @param null|string $middleText
     */
    public function setMiddleText(?string $middleText): void
    {
        $this->middleText = $middleText;
    }

    /**
     * @return null|string
     */
    public function getMiddleLink(): ?string
    {
        return $this->middleLink;
    }

    /**
     * @param null|string $middleLink
     */
    public function setMiddleLink(?string $middleLink): void
    {
        $this->middleLink = $middleLink;
    }
}