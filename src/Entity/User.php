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
 * @ORM\DiscriminatorMap({"ROLE_SELLER" = "Seller", "ROLE_BUYER" = "Buyer", "ROLE_ADMIN" = "Admin"})
 */
abstract class User extends BaseUser
{
    const ROLE_SELLER = "ROLE_SELLER";
    const ROLE_BUYER = "ROLE_BUYER";
    const ROLE_ADMIN = "ROLE_ADMIN";

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
     * @ORM\Column(type="string")
     */
    private $forgotPassword;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $city;

    /**
     * @var Collection
     *
     * One User has Many UserCars.
     * @ORM\OneToMany(targetEntity="UserCar", mappedBy="user")
     */
    private $cars;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $isHelper = 0;

    public function __construct()
    {
        parent::__construct();
        // your own logic
        $this->createdAt = new DateTime();
        $this->cars = new ArrayCollection();
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
     * @return Collection
     */
    public function getCars(): Collection
    {
        return $this->cars;
    }

    /**
     * @param Collection $cars
     */
    public function setCars(Collection $cars): void
    {
        $this->cars = $cars;
    }

    /**
     * @return bool
     */
    public function isHelper(): bool
    {
        return $this->isHelper;
    }

    /**
     * @param bool $isHelper
     */
    public function setIsHelper(bool $isHelper): void
    {
        $this->isHelper = $isHelper;
    }
}