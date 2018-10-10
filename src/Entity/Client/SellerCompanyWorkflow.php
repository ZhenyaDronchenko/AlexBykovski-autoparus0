<?php

namespace App\Entity\Client;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\Expression(
     *     "this.isMondayWork() or this.isTuesdayWork() or this.isWednesdayWork() or this.isThursdayWork() or this.isFridayWork() or this.isSaturdayWork() or this.isSundayWork()",
     *     message="Выберите хотя бы один рабочий день"
     * )
     *
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
     * @Assert\Expression(
     *     "((this.isMondayWork() or this.isTuesdayWork() or this.isWednesdayWork() or this.isThursdayWork() or this.isFridayWork()) and this.getWeekDaysStartAt()) or (!this.isMondayWork() and !this.isTuesdayWork() and !this.isWednesdayWork() and !this.isThursdayWork() and !this.isFridayWork())",
     *     message="Заполните начало рабочего времени для будних дней"
     * )
     *
     * @var DateTime|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $weekDaysStartAt;

    /**
     * @Assert\Expression(
     *     "((this.isMondayWork() or this.isTuesdayWork() or this.isWednesdayWork() or this.isThursdayWork() or this.isFridayWork()) and this.getWeekDaysEndAt()) or (!this.isMondayWork() and !this.isTuesdayWork() and !this.isWednesdayWork() and !this.isThursdayWork() and !this.isFridayWork())",
     *     message="Заполните конец рабочего времени для будних дней"
     * )
     *
     * @var DateTime|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $weekDaysEndAt;

    /**
     * @Assert\Expression(
     *     "((this.isSaturdayWork() or this.isSundayWork()) and this.getWeekendStartAt()) or (!this.isSaturdayWork() and !this.isSundayWork())",
     *     message="Заполните начало рабочего времени для выходных дней"
     * )
     *
     * @var DateTime|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $weekendStartAt;

    /**
     * @Assert\Expression(
     *     "((this.isSaturdayWork() or this.isSundayWork()) and this.getWeekendStartAt()) or (!this.isSaturdayWork() and !this.isSundayWork())",
     *     message="Заполните конец рабочего времени для выходных дней"
     * )
     *
     * @var DateTime|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $weekendEndAt;

    /**
     * @Assert\Expression(
     *     "this.isCash() or this.isCashless() or this.isCreditCard()",
     *     message="Выберите хотя бы один вариант"
     * )
     *
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