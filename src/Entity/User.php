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
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="user")
 *
 * @UniqueEntity(
 *     "email",
 *     message="Этот email уже зарегистрирован."
 * )
 *
 * @UniqueEntity(
 *     "phone",
 *     message="Этот телефон уже зарегистрирован."
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
    const ROLE_ADMIN_ARTICLE_WRITER = "ROLE_ADMIN_ARTICLE_WRITER";
    const ROLE_SHOW_POSTS_HOMEPAGE = "ROLE_SHOW_POSTS_HOMEPAGE";

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
     * @ORM\Column(type="string", unique=true, length=64)
     */
    protected $phone;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var Collection
     *
     * One User has One ForgotPassword.
     * @ORM\OneToMany(targetEntity="ForgotPassword", mappedBy="user")
     */
    private $forgotPasswords;

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
        $this->forgotPasswords = new ArrayCollection();
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
     * @return Collection
     */
    public function getForgotPasswords(): Collection
    {
        return $this->forgotPasswords;
    }

    /**
     * @param Collection $forgotPasswords
     */
    public function setForgotPasswords(Collection $forgotPasswords): void
    {
        $this->forgotPasswords = $forgotPasswords;
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

    public function toggleRole($role)
    {
        if($this->hasRole($role)){
            return $this->removeRole($role);
        }

        return $this->addRole($role);
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