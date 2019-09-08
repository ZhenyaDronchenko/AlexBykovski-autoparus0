<?php

namespace App\Entity\Request;


use App\Entity\Client\Client;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="city_catalog_request")
 */
class CityCatalogRequest
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
     * @var Client|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Client\Client")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    private $client;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Request\SparePartRequest", mappedBy="catalogRequest")
     */
    private $sparePartRequests;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $url;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $phoneBY;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $phoneRU;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $email;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * CityCatalogRequest constructor.
     */
    public function __construct()
    {
        $this->sparePartRequests = new ArrayCollection();
        $this->createdAt = new DateTime();
    }

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
     * @return Client|null
     */
    public function getClient(): ?Client
    {
        return $this->client;
    }

    /**
     * @param Client|null $client
     */
    public function setClient(?Client $client): void
    {
        $this->client = $client;
    }

    /**
     * @return Collection
     */
    public function getSparePartRequests(): Collection
    {
        return $this->sparePartRequests;
    }

    /**
     * @param Collection $sparePartRequests
     */
    public function setSparePartRequests(Collection $sparePartRequests): void
    {
        $this->sparePartRequests = $sparePartRequests;
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

    /**
     * @return null|string
     */
    public function getPhoneBY(): ?string
    {
        return $this->phoneBY;
    }

    /**
     * @param null|string $phoneBY
     */
    public function setPhoneBY(?string $phoneBY): void
    {
        $this->phoneBY = $phoneBY;
    }

    /**
     * @return null|string
     */
    public function getPhoneRU(): ?string
    {
        return $this->phoneRU;
    }

    /**
     * @param null|string $phoneRU
     */
    public function setPhoneRU(?string $phoneRU): void
    {
        $this->phoneRU = $phoneRU;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}