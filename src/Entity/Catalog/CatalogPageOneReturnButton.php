<?php

namespace App\Entity\Catalog;

use Doctrine\ORM\Mapping as ORM;

abstract class CatalogPageOneReturnButton
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
     * @ORM\Column(type="string")
     */
    protected $headline1;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text")
     */
    protected $text1;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    protected $headline2;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text")
     */
    protected $text2;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $returnButtonLink;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $returnButtonText;

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
    public function getHeadline1(): ?string
    {
        return $this->headline1;
    }

    /**
     * @param null|string $headline1
     */
    public function setHeadline1(?string $headline1): void
    {
        $this->headline1 = $headline1;
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
    public function getHeadline2(): ?string
    {
        return $this->headline2;
    }

    /**
     * @param null|string $headline2
     */
    public function setHeadline2(?string $headline2): void
    {
        $this->headline2 = $headline2;
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
    public function getReturnButtonLink(): ?string
    {
        return $this->returnButtonLink;
    }

    /**
     * @param null|string $returnButtonLink
     */
    public function setReturnButtonLink(?string $returnButtonLink): void
    {
        $this->returnButtonLink = $returnButtonLink;
    }

    /**
     * @return null|string
     */
    public function getReturnButtonText(): ?string
    {
        return $this->returnButtonText;
    }

    /**
     * @param null|string $returnButtonText
     */
    public function setReturnButtonText(?string $returnButtonText): void
    {
        $this->returnButtonText = $returnButtonText;
    }
}