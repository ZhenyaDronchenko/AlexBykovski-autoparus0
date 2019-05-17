<?php

namespace App\Entity\Forum;

use App\Entity\Client\Client;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="obd2forum_comment")
 */
class OBD2ForumComment
{
    /**
     * @var integer
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
     * @var OBD2ForumMessage
     * Many features have one product. This is the owning side.
     * @ORM\ManyToOne(targetEntity="OBD2ForumMessage", inversedBy="comments")
     * @ORM\JoinColumn(name="message_id", referencedColumnName="id")
     */
    private $message;

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
     * OBD2ForumComment constructor.
     * @param string $text
     * @param Client $user
     * @param OBD2ForumMessage $message
     */
    public function __construct(string $text, Client $user, OBD2ForumMessage $message)
    {
        $this->text = $text;
        $this->user = $user;
        $this->message = $message;

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
}