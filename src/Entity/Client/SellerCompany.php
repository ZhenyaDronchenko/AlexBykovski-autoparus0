<?php

namespace App\Entity\Client;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="seller_company")
 */
class SellerCompany
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
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $unp;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $companyName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $address;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $isSeller = 0;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $isService = 0;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $isNews = 0;

    /**
     * @var SellerCompanyWorkflow
     *
     * One SellerCompany has One SellerCompanyWorkflow.
     * @ORM\OneToOne(targetEntity="SellerCompanyWorkflow", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="workflow_id", referencedColumnName="id")
     */
    private $workflow;

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
    public function getUnp(): ?string
    {
        return $this->unp;
    }

    /**
     * @param null|string $unp
     */
    public function setUnp(?string $unp): void
    {
        $this->unp = $unp;
    }

    /**
     * @return null|string
     */
    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    /**
     * @param null|string $companyName
     */
    public function setCompanyName(?string $companyName): void
    {
        $this->companyName = $companyName;
    }

    /**
     * @return SellerCompanyWorkflow
     */
    public function getWorkflow(): SellerCompanyWorkflow
    {
        return $this->workflow;
    }

    /**
     * @param SellerCompanyWorkflow $workflow
     */
    public function setWorkflow(SellerCompanyWorkflow $workflow): void
    {
        $this->workflow = $workflow;
    }
}