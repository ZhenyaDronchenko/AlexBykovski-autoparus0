<?php

namespace App\Entity\UserData;

use App\Entity\Advert\AutoSparePart\AutoSparePartSpecificAdvert;
use App\Entity\Model;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_engine")
 */
class UserEngine
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
    private $type;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $capacity;

    /**
     * @var Model
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Model")
     * @ORM\JoinColumn(name="model_id", referencedColumnName="id")
     */
    private $model;

    /**
     * @var AutoSparePartSpecificAdvert|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Advert\AutoSparePart\AutoSparePartSpecificAdvert")
     * @ORM\JoinColumn(name="specific_advert_initiator_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $specificAdvertInitiator;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * UserEngine constructor.
     * @param null|string $type
     * @param null|string $name
     * @param null|string $capacity
     * @param Model $model
     * @param AutoSparePartSpecificAdvert|null $specificAdvertInitiator
     */
    public function __construct(
        string $type = null,
        string $name = null,
        string $capacity = null,
        Model $model = null,
        AutoSparePartSpecificAdvert $specificAdvertInitiator = null
    )
    {
        $this->type = $type;
        $this->name = $name;
        $this->capacity = $capacity;
        $this->model = $model;
        $this->specificAdvertInitiator = $specificAdvertInitiator;
        $this->createdAt = new DateTime();
    }

    static function createByAutoSparePartSpecificAdvert(AutoSparePartSpecificAdvert $advert)
    {
        $type = $advert->getEngineType();
        $name = $advert->getEngineName();
        $capacity = $advert->getEngineCapacity();
        $model = $advert->getModel();

        return new UserEngine($type, $name, $capacity, $model, $advert);
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
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param null|string $type
     */
    public function setType(?string $type): void
    {
        $this->type = $type;
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
    public function getCapacity(): ?string
    {
        return $this->capacity;
    }

    /**
     * @param null|string $capacity
     */
    public function setCapacity(?string $capacity): void
    {
        $this->capacity = $capacity;
    }

    /**
     * @return Model
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * @param Model $model
     */
    public function setModel(Model $model): void
    {
        $this->model = $model;
    }

    /**
     * @return AutoSparePartSpecificAdvert|null
     */
    public function getSpecificAdvertInitiator(): ?AutoSparePartSpecificAdvert
    {
        return $this->specificAdvertInitiator;
    }

    /**
     * @param AutoSparePartSpecificAdvert|null $specificAdvertInitiator
     */
    public function setSpecificAdvertInitiator(?AutoSparePartSpecificAdvert $specificAdvertInitiator): void
    {
        $this->specificAdvertInitiator = $specificAdvertInitiator;
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
}