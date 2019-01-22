<?php

namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EmailDomainRepository")
 * @ORM\Table(name="email_domain")
 */
class EmailDomain
{
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
     * @ORM\Column(type="string", unique=true, length=128)
     */
    private $emailEnd;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $domain;

    /**
     * @var RegistrationPage|null
     *
     * @ORM\ManyToOne(targetEntity="RegistrationPage", inversedBy="emailDomains")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id")
     */
    private $page;

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
    public function getEmailEnd(): ?string
    {
        return $this->emailEnd;
    }

    /**
     * @param null|string $emailEnd
     */
    public function setEmailEnd(?string $emailEnd): void
    {
        $this->emailEnd = $emailEnd;
    }

    /**
     * @return null|string
     */
    public function getDomain(): ?string
    {
        return $this->domain;
    }

    /**
     * @param null|string $domain
     */
    public function setDomain(?string $domain): void
    {
        $this->domain = $domain;
    }

    /**
     * @return RegistrationPage|null
     */
    public function getPage(): ?RegistrationPage
    {
        return $this->page;
    }

    /**
     * @param RegistrationPage|null $page
     */
    public function setPage(?RegistrationPage $page): void
    {
        $this->page = $page;
    }
}