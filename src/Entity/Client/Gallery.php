<?php

namespace App\Entity\Client;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="gallery")
 */
class Gallery
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
     * @var Client
     * One Gallery has One Client.
     * @ORM\OneToOne(targetEntity="Client", inversedBy="gallery")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    private $client;

    /**
     * @var Collection
     *
     * One Gallery has Many GalleryPhotos.
     * @ORM\OneToMany(targetEntity="GalleryPhoto", mappedBy="gallery")
     */
    private $photos;

    /**
     * Gallery constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->photos = new ArrayCollection();
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

    /**
     * @return Collection
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    /**
     * @param Collection $photos
     */
    public function setPhotos(Collection $photos): void
    {
        $this->photos = $photos;
    }
}