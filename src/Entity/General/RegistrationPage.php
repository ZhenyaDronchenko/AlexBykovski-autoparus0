<?php

namespace App\Entity\General;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="registration_page")
 */
class RegistrationPage
{
    const NAME_COOKIE_KEY = "user-name";
    const EMAIL_COOKIE_KEY = "user-email";
    const PHONE_COOKIE_KEY = "user-phone";
    const TERMS_COOKIE_KEY = "user-terms";
    const SAVING_COOKIE_KEY = "reg-values";

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
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="EmailDomain", mappedBy="page", cascade={"persist", "remove"})
     */
    private $emailDomains;

    /**
     * RegistrationPage constructor.
     */
    public function __construct()
    {
        $this->emailDomains = new ArrayCollection();
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
     * @return Collection
     */
    public function getEmailDomains(): Collection
    {
        return $this->emailDomains;
    }

    /**
     * @param Collection $emailDomains
     */
    public function setEmailDomains(Collection $emailDomains): void
    {
        $this->emailDomains = $emailDomains;
    }

    /**
     * @param $emailEnd
     *
     * @return EmailDomain|null
     */
    public function findEmailDomain($emailEnd)
    {
        $elements = $this->emailDomains->filter(function(EmailDomain $item) use ($emailEnd) {
            return $item->getEmailEnd() === $emailEnd;
        });

        return $elements->count() ? $elements->first() : null;
    }
}