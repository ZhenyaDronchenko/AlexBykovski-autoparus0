<?php

namespace App\Entity\Error;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="code_obd2error")
 */
class CodeOBD2Error
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
     * @ORM\Column(type="string")
     */
    private $code;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $url;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $transcriptRu;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $transcriptEn;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text")
     */
    private $reason;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text")
     */
    private $advice;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $urlToCatalog;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $active = 0;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $isOftenSearch = 0;

    /**
     * @var TypeOBD2Error|null
     *
     * @ORM\ManyToOne(targetEntity="TypeOBD2Error", inversedBy="codes")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     */
    private $type;

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
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param null|string $code
     */
    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return null|string
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param null|string $url
     */
    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return null|string
     */
    public function getTranscriptRu(): ?string
    {
        return $this->transcriptRu;
    }

    /**
     * @param null|string $transcriptRu
     */
    public function setTranscriptRu(?string $transcriptRu): void
    {
        $this->transcriptRu = $transcriptRu;
    }

    /**
     * @return null|string
     */
    public function getTranscriptEn(): ?string
    {
        return $this->transcriptEn;
    }

    /**
     * @param null|string $transcriptEn
     */
    public function setTranscriptEn(?string $transcriptEn): void
    {
        $this->transcriptEn = $transcriptEn;
    }

    /**
     * @return null|string
     */
    public function getReason(): ?string
    {
        return $this->reason;
    }

    /**
     * @param null|string $reason
     */
    public function setReason(?string $reason): void
    {
        $this->reason = $reason;
    }

    /**
     * @return null|string
     */
    public function getAdvice(): ?string
    {
        return $this->advice;
    }

    /**
     * @param null|string $advice
     */
    public function setAdvice(?string $advice): void
    {
        $this->advice = $advice;
    }

    /**
     * @return null|string
     */
    public function getUrlToCatalog(): ?string
    {
        return $this->urlToCatalog;
    }

    /**
     * @param null|string $urlToCatalog
     */
    public function setUrlToCatalog(?string $urlToCatalog): void
    {
        $this->urlToCatalog = $urlToCatalog;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    /**
     * @return bool
     */
    public function isOftenSearch(): bool
    {
        return $this->isOftenSearch;
    }

    /**
     * @param bool $isOftenSearch
     */
    public function setIsOftenSearch(bool $isOftenSearch): void
    {
        $this->isOftenSearch = $isOftenSearch;
    }

    /**
     * @return TypeOBD2Error|null
     */
    public function getType(): ?TypeOBD2Error
    {
        return $this->type;
    }

    /**
     * @param TypeOBD2Error|null $type
     */
    public function setType(?TypeOBD2Error $type): void
    {
        $this->type = $type;
    }
}