<?php

namespace App\Entity\Forum;

use App\Entity\Client\Client;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass="App\Repository\Forum\OBD2Forum\OBD2ForumMessageRepository")
 * @ORM\Table(name="obd2forum_message")
 */
class OBD2ForumMessage
{
    const QUESTION_TYPE = "question";
    const EXPERIENCE_TYPE = "experience";

    static $availableTypes = [self::QUESTION_TYPE, self::EXPERIENCE_TYPE];

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $text;

    /**
     * @var Client
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Client\Client")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $type;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="OBD2ForumComment", mappedBy="message", cascade={"persist", "remove"})
     */
    private $comments;

    /**
     * @var OBD2ForumMessageTechnicalData
     *
     * @ORM\OneToOne(targetEntity="OBD2ForumMessageTechnicalData", mappedBy="message", cascade={"persist"})
     */
    private $technicalData;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * OBD2ForumMessage constructor.
     * @param string $text
     * @param Client $user
     * @param OBD2ForumMessageTechnicalData $technicalData
     * @param string $type
     */
    public function __construct(
        string $text,
        Client $user,
        OBD2ForumMessageTechnicalData $technicalData,
        $type = self::QUESTION_TYPE)
    {
        $this->text = $text;
        $this->user = $user;
        $this->type = $type;
        $this->technicalData = $technicalData;
        $this->technicalData->setMessage($this);

        $this->comments = new ArrayCollection();

        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
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
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * @return Client
     */
    public function getUser(): Client
    {
        return $this->user;
    }

    /**
     * @param Client $user
     */
    public function setUser(Client $user): void
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return Collection
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    /**
     * @param Collection $comments
     */
    public function setComments(Collection $comments): void
    {
        $this->comments = $comments;
    }

    /**
     * @return OBD2ForumMessageTechnicalData
     */
    public function getTechnicalData(): OBD2ForumMessageTechnicalData
    {
        return $this->technicalData;
    }

    /**
     * @param OBD2ForumMessageTechnicalData $technicalData
     */
    public function setTechnicalData(OBD2ForumMessageTechnicalData $technicalData): void
    {
        $this->technicalData = $technicalData;
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
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime $updatedAt
     */
    public function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function adddComment(OBD2ForumComment $comment)
    {
        $this->comments->add($comment);
    }

    public function toArray()
    {
        return [
            "id" => $this->id,
            "userName" => $this->getUser()->getName(),
            "userPhoto" => $this->getUser()->getPhoto() ? '/images/' . $this->getUser()->getPhoto()->getImage() : "",
            "brand" => $this->technicalData->getBrand()->getName(),
            "model" => $this->technicalData->getModel()->getName(),
            "urlModel" => $this->technicalData->getModel()->getUrl(),
            "errorType" => $this->technicalData->getType()->getDesignation(),
            "errorCode" => $this->technicalData->getCode()->getCode(),
            "type" => $this->type,
            "text" => $this->text,
            "createdAt" => $this->createdAt->format("d.m.Y"),
            "comments" => $this->commentsToArray(),
        ];
    }

    private function commentsToArray()
    {
        $comments = [];

        /** @var OBD2ForumComment $comment */
        foreach ($this->comments as $comment)
        {
            $comments[] = $comment->toArray();
        }

        return $comments;
    }
}