<?php

namespace App\Entity\Client;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="seller_company")
 */
class SellerCompany
{
    const ACTIVITY_URL_SPARE_PART_SELLER = "spare-part-seller";
    const ACTIVITY_URL_AUTO_SELLER = "auto-seller";
    const ACTIVITY_URL_SERVICE = "service";
    const ACTIVITY_URL_NEWS = "news";
    const ACTIVITY_URL_TOURISM = "tourism";

    static $activities = [
        self::ACTIVITY_URL_SPARE_PART_SELLER => "Авто-мото запчасти",
        self::ACTIVITY_URL_AUTO_SELLER => "Авто-мото транспорт",
        self::ACTIVITY_URL_SERVICE => "Услуги / СТО / Сервисы",
        self::ACTIVITY_URL_NEWS => "Новости / Статьи",
        self::ACTIVITY_URL_TOURISM => "Туризм / Путешествия",
    ];

    static $activitiesField = [
        self::ACTIVITY_URL_SPARE_PART_SELLER => "isSparePartSeller",
        self::ACTIVITY_URL_AUTO_SELLER => "isAutoSeller",
        self::ACTIVITY_URL_SERVICE => "isService",
        self::ACTIVITY_URL_NEWS => "isNews",
        self::ACTIVITY_URL_TOURISM => "isTourism",
    ];

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
    private $unp;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $companyName;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $city;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $address;

    /**
     * @Assert\Expression(
     *     "this.isSparePartSeller() or this.isAutoSeller() or this.isService() or this.isNews()",
     *     message="Выберите хотя бы одно значение"
     * )
     *
     * @var boolean
     *
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $isSparePartSeller = 0;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $isAutoSeller = 0;

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
     * @var boolean
     *
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $isTourism = 0;

    /**
     * @Assert\Valid()
     *
     * @var SellerCompanyWorkflow
     *
     * One SellerCompany has One SellerCompanyWorkflow.
     * @ORM\OneToOne(targetEntity="SellerCompanyWorkflow", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="workflow_id", referencedColumnName="id")
     */
    private $workflow;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $activityDescription;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $additionalPhone;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $additionalPhone2;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $additionalPhone3;

    /**
     * @var SellerData|null
     *
     * One SellerCompany has One SellerData.
     * @ORM\OneToOne(targetEntity="SellerData", mappedBy="sellerCompany")
     */
    private $sellerData;

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

    /**
     * @return null|string
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param null|string $address
     */
    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    /**
     * @return bool
     */
    public function isSparePartSeller(): bool
    {
        return $this->isSparePartSeller;
    }

    /**
     * @param bool $isSparePartSeller
     */
    public function setIsSparePartSeller(bool $isSparePartSeller): void
    {
        $this->isSparePartSeller = $isSparePartSeller;
    }

    /**
     * @return bool
     */
    public function isAutoSeller(): bool
    {
        return $this->isAutoSeller;
    }

    /**
     * @param bool $isAutoSeller
     */
    public function setIsAutoSeller(bool $isAutoSeller): void
    {
        $this->isAutoSeller = $isAutoSeller;
    }

    /**
     * @return bool
     */
    public function isService(): bool
    {
        return $this->isService;
    }

    /**
     * @param bool $isService
     */
    public function setIsService(bool $isService): void
    {
        $this->isService = $isService;
    }

    /**
     * @return bool
     */
    public function isNews(): bool
    {
        return $this->isNews;
    }

    /**
     * @param bool $isNews
     */
    public function setIsNews(bool $isNews): void
    {
        $this->isNews = $isNews;
    }

    /**
     * @return bool
     */
    public function isTourism(): bool
    {
        return $this->isTourism;
    }

    /**
     * @param bool $isTourism
     */
    public function setIsTourism(bool $isTourism): void
    {
        $this->isTourism = $isTourism;
    }

    /**
     * @return null|string
     */
    public function getActivityDescription(): ?string
    {
        return $this->activityDescription;
    }

    /**
     * @param null|string $activityDescription
     */
    public function setActivityDescription(?string $activityDescription): void
    {
        $this->activityDescription = $activityDescription;
    }

    /**
     * @return null|string
     */
    public function getAdditionalPhone(): ?string
    {
        return $this->additionalPhone;
    }

    /**
     * @param null|string $additionalPhone
     */
    public function setAdditionalPhone(?string $additionalPhone): void
    {
        $this->additionalPhone = $additionalPhone;
    }

    /**
     * @return null|string
     */
    public function getAdditionalPhone2(): ?string
    {
        return $this->additionalPhone2;
    }

    /**
     * @param null|string $additionalPhone2
     */
    public function setAdditionalPhone2(?string $additionalPhone2): void
    {
        $this->additionalPhone2 = $additionalPhone2;
    }

    /**
     * @return null|string
     */
    public function getAdditionalPhone3(): ?string
    {
        return $this->additionalPhone3;
    }

    /**
     * @param null|string $additionalPhone3
     */
    public function setAdditionalPhone3(?string $additionalPhone3): void
    {
        $this->additionalPhone3 = $additionalPhone3;
    }

    /**
     * @return null|string
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param null|string $city
     */
    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return SellerData|null
     */
    public function getSellerData(): ?SellerData
    {
        return $this->sellerData;
    }

    /**
     * @param SellerData|null $sellerData
     */
    public function setSellerData(?SellerData $sellerData): void
    {
        $this->sellerData = $sellerData;
    }
}