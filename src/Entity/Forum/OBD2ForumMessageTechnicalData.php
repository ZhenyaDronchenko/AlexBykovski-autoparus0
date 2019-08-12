<?php

namespace App\Entity\Forum;

use App\Entity\Brand;
use App\Entity\Error\CodeOBD2Error;
use App\Entity\Error\TypeOBD2Error;
use App\Entity\Model;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="obd2forum_message_technical_data")
 */
class OBD2ForumMessageTechnicalData
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
     * @var Brand
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Brand")
     * @ORM\JoinColumn(name="brand_id", referencedColumnName="id")
     */
    private $brand;

    /**
     * @var Model
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Model")
     * @ORM\JoinColumn(name="model_id", referencedColumnName="id")
     */
    private $model;

    /**
     * @var TypeOBD2Error
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Error\TypeOBD2Error")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     */
    private $type;

    /**
     * @var CodeOBD2Error
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Error\CodeOBD2Error")
     * @ORM\JoinColumn(name="code_id", referencedColumnName="id")
     */
    private $code;

    /**
     * @var OBD2ForumMessage
     * @ORM\OneToOne(targetEntity="OBD2ForumMessage", inversedBy="technicalData")
     * @ORM\JoinColumn(name="message_id", referencedColumnName="id")
     */
    private $message;

    /**
     * OBD2ForumMessageTechnicalData constructor.
     * @param Brand $brand
     * @param Model $model
     * @param TypeOBD2Error $type
     * @param CodeOBD2Error $code
     * @param OBD2ForumMessage|null  $message
     */
    public function __construct(Brand $brand, Model $model, TypeOBD2Error $type, CodeOBD2Error $code, $message = null)
    {
        $this->brand = $brand;
        $this->model = $model;
        $this->type = $type;
        $this->code = $code;
        $this->message = $message;
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
     * @return Brand
     */
    public function getBrand(): Brand
    {
        return $this->brand;
    }

    /**
     * @param Brand $brand
     */
    public function setBrand(Brand $brand): void
    {
        $this->brand = $brand;
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
     * @return CodeOBD2Error
     */
    public function getCode(): CodeOBD2Error
    {
        return $this->code;
    }

    /**
     * @param CodeOBD2Error $code
     */
    public function setCode(CodeOBD2Error $code): void
    {
        $this->code = $code;
    }

    /**
     * @return OBD2ForumMessage
     */
    public function getMessage(): OBD2ForumMessage
    {
        return $this->message;
    }

    /**
     * @param OBD2ForumMessage $message
     */
    public function setMessage(OBD2ForumMessage $message): void
    {
        $this->message = $message;
    }
}