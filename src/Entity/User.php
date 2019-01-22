<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 *
 * @UniqueEntity(
 *     "email",
 *     message="Этот email уже зарегистрирован."
 * )
 *
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="role", type="string")
 * @ORM\DiscriminatorMap({"ROLE_CLIENT" = "App\Entity\Client\Client", "ROLE_ADMIN" = "Admin"})
 */
abstract class User extends BaseUser
{
    const ROLE_SELLER = "ROLE_SELLER";
    const ROLE_BUYER = "ROLE_BUYER";
    const ROLE_ADMIN = "ROLE_ADMIN";
    const ROLE_CLIENT = "ROLE_CLIENT";

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string|null
     *
     * @Assert\NotBlank(message="Заполните имя", groups={"registration", "edit_profile"})
     *
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var string|null
     *
     * @Assert\NotBlank(message="Введите телефон", groups={"registration", "edit_profile"})
     *
     * @ORM\Column(type="string")
     */
    private $phone;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var ForgotPassword|null
     *
     * One User has One ForgotPassword.
     * @ORM\OneToOne(targetEntity="ForgotPassword", mappedBy="user")
     */
    private $forgotPassword;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $activateCode;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $activatedAt;

    public function __construct()
    {
        parent::__construct();
        // your own logic
        $this->createdAt = new DateTime();
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
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return null|string
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param null|string $phone
     */
    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return ForgotPassword|null
     */
    public function getForgotPassword(): ?ForgotPassword
    {
        return $this->forgotPassword;
    }

    /**
     * @param ForgotPassword|null $forgotPassword
     */
    public function setForgotPassword(?ForgotPassword $forgotPassword): void
    {
        $this->forgotPassword = $forgotPassword;
    }

    /**
     * @return null|string
     */
    public function getActivateCode(): ?string
    {
        return $this->activateCode;
    }

    /**
     * @param null|string $activateCode
     */
    public function setActivateCode(?string $activateCode): void
    {
        $this->activateCode = $activateCode;
    }

    /**
     * @return DateTime|null
     */
    public function getActivatedAt(): ?DateTime
    {
        return $this->activatedAt;
    }

    /**
     * @param DateTime|null $activatedAt
     */
    public function setActivatedAt(?DateTime $activatedAt): void
    {
        $this->activatedAt = $activatedAt;
    }

    /**
     * @param bool $isHelper
     */
    public function setIsHelper(bool $isHelper): void
    {
        $this->isHelper = $isHelper;
    }

    public function isBuyer()
    {
        return $this->hasRole(User::ROLE_BUYER);
    }

    public function isSeller()
    {
        return $this->hasRole(User::ROLE_SELLER);
    }
}