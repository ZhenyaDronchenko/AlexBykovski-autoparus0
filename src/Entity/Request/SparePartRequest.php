<?php

namespace App\Entity\Request;

use App\Entity\SparePart;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="spare_part_request")
 */
class SparePartRequest
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
     * @var CityCatalogRequest
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Request\CityCatalogRequest", inversedBy="sparePartRequests")
     * @ORM\JoinColumn(name="catalog_request_id", referencedColumnName="id")
     */
    private $catalogRequest;

    /**
     * @var SparePart|
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\SparePart")
     * @ORM\JoinColumn(name="spare_part_id", referencedColumnName="id")
     */
    private $sparePart;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $sparePartNumber;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

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
     * @return CityCatalogRequest
     */
    public function getCatalogRequest(): CityCatalogRequest
    {
        return $this->catalogRequest;
    }

    /**
     * @param CityCatalogRequest $catalogRequest
     */
    public function setCatalogRequest(CityCatalogRequest $catalogRequest): void
    {
        $this->catalogRequest = $catalogRequest;
    }

    /**
     * @return SparePart
     */
    public function getSparePart(): SparePart
    {
        return $this->sparePart;
    }

    /**
     * @param SparePart $sparePart
     */
    public function setSparePart(SparePart $sparePart): void
    {
        $this->sparePart = $sparePart;
    }

    /**
     * @return null|string
     */
    public function getSparePartNumber(): ?string
    {
        return $this->sparePartNumber;
    }

    /**
     * @param null|string $sparePartNumber
     */
    public function setSparePartNumber(?string $sparePartNumber): void
    {
        $this->sparePartNumber = $sparePartNumber;
    }

    /**
     * @return null|string
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param null|string $comment
     */
    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }
}