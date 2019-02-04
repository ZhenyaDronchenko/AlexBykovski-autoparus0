<?php

namespace App\Entity\UserData;

use App\Entity\Advert\AutoSparePart\AutoSparePartSpecificAdvert;
use App\Entity\Error\CodeOBD2Error;
use App\Entity\Error\TypeOBD2Error;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_obd2error_code")
 */
class UserOBD2ErrorCode
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
     * @var TypeOBD2Error
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Error\TypeOBD2Error")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     */
    private $type;

    /**
     * @var User|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    private $user;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", options={"default" : 1})
     */
    private $counter = 1;

    /**
     * UserEngine constructor.
     * @param string $code
     * @param TypeOBD2Error $type
     * @param User|null $user
     */
    public function __construct(
        string $code,
        TypeOBD2Error $type,
        User $user = null
    )
    {
        $this->code = $code;
        $this->type = $type;
        $this->user = $user;
        $this->createdAt = new DateTime();
    }

    /**
     * @return CodeOBD2Error
     */
    public function createNewCode()
    {
        $newCode = new CodeOBD2Error();

        $newCode->setType($this->type);
        $newCode->setCode($this->code);

        return $newCode;
    }

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
     * @return TypeOBD2Error
     */
    public function getType(): TypeOBD2Error
    {
        return $this->type;
    }

    /**
     * @param TypeOBD2Error $type
     */
    public function setType(TypeOBD2Error $type): void
    {
        $this->type = $type;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     */
    public function setUser(?User $user): void
    {
        $this->user = $user;
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
    public function getCounter(): int
    {
        return $this->counter;
    }

    /**
     * @param int $counter
     */
    public function setCounter(int $counter): void
    {
        $this->counter = $counter;
    }

    public function increaseCounter(): void
    {
        ++$this->counter;
    }
}