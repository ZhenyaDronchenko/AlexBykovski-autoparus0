<?php

namespace App\Entity\Client;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="seller_company_workflow")
 */
class SellerCompanyWorkflow
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
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $isMondayWork = 0;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $isTuesdayWork = 0;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $isWednesdayWork = 0;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $isThursdayWork = 0;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $isFridayWork = 0;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $isSaturdayWork = 0;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $isSundayWork = 0;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(type="datetime")
     */
    private $weekDaysStartAt;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(type="datetime")
     */
    private $weekDaysEndAt;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(type="datetime")
     */
    private $weekendStartAt;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(type="datetime")
     */
    private $weekendEndAt;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $isCash = 0;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $isCashless = 0;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $isCreditCard = 0;

    /**
     * @var boolean
     *
     * @ORM\Column(type="string")
     */
    private $delivery = 0;

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
     * @return bool
     */
    public function isMondayWork(): bool
    {
        return $this->isMondayWork;
    }

    /**
     * @param bool $isMondayWork
     */
    public function setIsMondayWork(bool $isMondayWork): void
    {
        $this->isMondayWork = $isMondayWork;
    }

    /**
     * @return bool
     */
    public function isTuesdayWork(): bool
    {
        return $this->isTuesdayWork;
    }

    /**
     * @param bool $isTuesdayWork
     */
    public function setIsTuesdayWork(bool $isTuesdayWork): void
    {
        $this->isTuesdayWork = $isTuesdayWork;
    }

    /**
     * @return bool
     */
    public function isWednesdayWork(): bool
    {
        return $this->isWednesdayWork;
    }

    /**
     * @param bool $isWednesdayWork
     */
    public function setIsWednesdayWork(bool $isWednesdayWork): void
    {
        $this->isWednesdayWork = $isWednesdayWork;
    }

    /**
     * @return bool
     */
    public function isThursdayWork(): bool
    {
        return $this->isThursdayWork;
    }

    /**
     * @param bool $isThursdayWork
     */
    public function setIsThursdayWork(bool $isThursdayWork): void
    {
        $this->isThursdayWork = $isThursdayWork;
    }

    /**
     * @return bool
     */
    public function isFridayWork(): bool
    {
        return $this->isFridayWork;
    }

    /**
     * @param bool $isFridayWork
     */
    public function setIsFridayWork(bool $isFridayWork): void
    {
        $this->isFridayWork = $isFridayWork;
    }

    /**
     * @return bool
     */
    public function isSaturdayWork(): bool
    {
        return $this->isSaturdayWork;
    }

    /**
     * @param bool $isSaturdayWork
     */
    public function setIsSaturdayWork(bool $isSaturdayWork): void
    {
        $this->isSaturdayWork = $isSaturdayWork;
    }

    /**
     * @return bool
     */
    public function isSundayWork(): bool
    {
        return $this->isSundayWork;
    }

    /**
     * @param bool $isSundayWork
     */
    public function setIsSundayWork(bool $isSundayWork): void
    {
        $this->isSundayWork = $isSundayWork;
    }

    /**
     * @return DateTime|null
     */
    public function getWeekDaysStartAt(): ?DateTime
    {
        return $this->weekDaysStartAt;
    }

    /**
     * @param DateTime|null $weekDaysStartAt
     */
    public function setWeekDaysStartAt(?DateTime $weekDaysStartAt): void
    {
        $this->weekDaysStartAt = $weekDaysStartAt;
    }

    /**
     * @return DateTime|null
     */
    public function getWeekDaysEndAt(): ?DateTime
    {
        return $this->weekDaysEndAt;
    }

    /**
     * @param DateTime|null $weekDaysEndAt
     */
    public function setWeekDaysEndAt(?DateTime $weekDaysEndAt): void
    {
        $this->weekDaysEndAt = $weekDaysEndAt;
    }

    /**
     * @return DateTime|null
     */
    public function getWeekendStartAt(): ?DateTime
    {
        return $this->weekendStartAt;
    }

    /**
     * @param DateTime|null $weekendStartAt
     */
    public function setWeekendStartAt(?DateTime $weekendStartAt): void
    {
        $this->weekendStartAt = $weekendStartAt;
    }

    /**
     * @return DateTime|null
     */
    public function getWeekendEndAt(): ?DateTime
    {
        return $this->weekendEndAt;
    }

    /**
     * @param DateTime|null $weekendEndAt
     */
    public function setWeekendEndAt(?DateTime $weekendEndAt): void
    {
        $this->weekendEndAt = $weekendEndAt;
    }

    /**
     * @return bool
     */
    public function isCash(): bool
    {
        return $this->isCash;
    }

    /**
     * @param bool $isCash
     */
    public function setIsCash(bool $isCash): void
    {
        $this->isCash = $isCash;
    }

    /**
     * @return bool
     */
    public function isCashless(): bool
    {
        return $this->isCashless;
    }

    /**
     * @param bool $isCashless
     */
    public function setIsCashless(bool $isCashless): void
    {
        $this->isCashless = $isCashless;
    }

    /**
     * @return bool
     */
    public function isCreditCard(): bool
    {
        return $this->isCreditCard;
    }

    /**
     * @param bool $isCreditCard
     */
    public function setIsCreditCard(bool $isCreditCard): void
    {
        $this->isCreditCard = $isCreditCard;
    }

    /**
     * @return bool
     */
    public function isDelivery(): bool
    {
        return $this->delivery;
    }

    /**
     * @param bool $delivery
     */
    public function setDelivery(bool $delivery): void
    {
        $this->delivery = $delivery;
    }
}