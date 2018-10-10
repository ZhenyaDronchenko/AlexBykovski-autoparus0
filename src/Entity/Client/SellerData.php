<?php

namespace App\Entity\Client;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="seller_data")
 */
class SellerData
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
     * @var SellerCompany|null
     *
     * One SellerData has One SellerCompany.
     * @ORM\OneToOne(targetEntity="SellerCompany", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     */
    private $sellerCompany;

    /**
     * @var Client
     *
     * One SellerData has One Client.
     * @ORM\OneToOne(targetEntity="Client", inversedBy="sellerData")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    private $client;

    /**
     * @param Client $client
     * SellerData constructor.
     */
    public function __construct($client)
    {
        $this->client = $client;
        $this->client->addRole(User::ROLE_SELLER);
        $this->sellerCompany = new SellerCompany();
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
     * @return SellerCompany|null
     */
    public function getSellerCompany(): ?SellerCompany
    {
        return $this->sellerCompany;
    }

    /**
     * @param SellerCompany|null $sellerCompany
     */
    public function setSellerCompany(?SellerCompany $sellerCompany): void
    {
        $this->sellerCompany = $sellerCompany;
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @param Client $client
     */
    public function setClient(Client $client): void
    {
        $this->client = $client;
    }
}