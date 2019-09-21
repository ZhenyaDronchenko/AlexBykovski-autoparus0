<?php

namespace App\Entity\Article;

use App\Entity\Client\Client;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="article_comment")
 */
class ArticleComment
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
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $comment;

    /**
     * @var Article
     *
     * @ORM\ManyToOne(targetEntity="Article", inversedBy="comments")
     * @ORM\JoinColumn(name="article_id", referencedColumnName="id")
     */
    private $article;

    /**
     * @var ArticleComment|null
     *
     * Many ArticleComments have One ArticleComment.
     * @ORM\ManyToOne(targetEntity="ArticleComment", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

    /**
     * @var Collection
     *
     * One ArticleComment has Many ArticleComments.
     *
     * @ORM\OneToMany(targetEntity="ArticleComment", mappedBy="parent")
     */
    private $children;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var Client
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Client\Client")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    private $author;

    /**
     * ArticleComment constructor.
     *
     * @param string $comment
     * @param Article $article
     * @param Client $author
     * @param ArticleComment|null $parent
     */
    public function __construct(string $comment, Article $article, Client $author, ?ArticleComment $parent = null)
    {
        $this->comment = $comment;
        $this->article = $article;
        $this->author = $author;
        $this->parent = $parent;

        $this->children = new ArrayCollection();
        $this->createdAt = new DateTime();
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
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     */
    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }

    /**
     * @return Article
     */
    public function getArticle(): Article
    {
        return $this->article;
    }

    /**
     * @param Article $article
     */
    public function setArticle(Article $article): void
    {
        $this->article = $article;
    }

    /**
     * @return ArticleComment|null
     */
    public function getParent(): ?ArticleComment
    {
        return $this->parent;
    }

    /**
     * @param ArticleComment|null $parent
     */
    public function setParent(?ArticleComment $parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @return Collection
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    /**
     * @param Collection $children
     */
    public function setChildren(Collection $children): void
    {
        $this->children = $children;
    }

    public function addChild(ArticleComment $comment)
    {
        $this->children->add($comment);
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
     * @return Client
     */
    public function getAuthor(): Client
    {
        return $this->author;
    }

    /**
     * @param Client $author
     */
    public function setAuthor(Client $author): void
    {
        $this->author = $author;
    }

    public function toArray()
    {
        $comment = [
            "id" => $this->id,
            "text" => $this->comment,
            "createdAt" => $this->createdAt->format("d-m-Y H:i"),
            "author" => [
                "photo" => '/images/' . ($this->author->getPhoto() ? $this->author->getPhoto()->getImage() : ""),
                "name" => $this->author->getName()
            ],
            "children" => [],
            "parent" => $this->parent ? $this->parent->getId() : null,
        ];

        /** @var ArticleComment $child */
        foreach ($this->children as $child){
            $comment["children"][] = $child->toArray();
        }

        return $comment;
    }
}