<?php

namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="recovery_password_page")
 */
class RecoveryPasswordPage
{
    /**
     * @var integer|null
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
    private $headline;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text")
     */
    private $textBottom;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $textModal;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    protected $returnButtonLink;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    protected $returnButtonText;

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
    public function getHeadline(): ?string
    {
        return $this->headline;
    }

    /**
     * @param null|string $headline
     */
    public function setHeadline(?string $headline): void
    {
        $this->headline = $headline;
    }

    /**
     * @return null|string
     */
    public function getTextBottom(): ?string
    {
        return $this->textBottom;
    }

    /**
     * @param null|string $textBottom
     */
    public function setTextBottom(?string $textBottom): void
    {
        $this->textBottom = $textBottom;
    }

    /**
     * @return null|string
     */
    public function getTextModal(): ?string
    {
        return $this->textModal;
    }

    /**
     * @param null|string $textModal
     */
    public function setTextModal(?string $textModal): void
    {
        $this->textModal = $textModal;
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