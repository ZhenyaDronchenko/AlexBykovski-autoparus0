<?php

namespace App\Entity\Article;

use App\Handler\ResizeImageHandler;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * @ORM\Entity
 * @ORM\Table(name="article_image")
 * @ORM\HasLifecycleCallbacks()
 */
class ArticleImage
{
    const TYPE_MAIN = "main";
    const TYPE_SIMPLE = "simple";

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $image;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $imageThumbnail;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $comment;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $author;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $imageText;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $text;

    /**
     * @var Article|null
     *
     * Many features have one product. This is the owning side.
     * @ORM\ManyToOne(targetEntity="Article", inversedBy="articleImages")
     * @ORM\JoinColumn(name="article_id", referencedColumnName="id")
     */
    private $article;

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
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param null|string $image
     */
    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    /**
     * @return null|string
     */
    public function getImageThumbnail(): ?string
    {
        return $this->imageThumbnail;
    }

    /**
     * @param null|string $imageThumbnail
     */
    public function setImageThumbnail(?string $imageThumbnail): void
    {
        $this->imageThumbnail = $imageThumbnail;
    }

    /**
     * @return null|string
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param null|string $comment
     */
    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }

    /**
     * @return null|string
     */
    public function getAuthor(): ?string
    {
        return $this->author;
    }

    /**
     * @param null|string $author
     */
    public function setAuthor(?string $author): void
    {
        $this->author = $author;
    }

    /**
     * @return null|string
     */
    public function getImageText(): ?string
    {
        return $this->imageText;
    }

    /**
     * @param null|string $imageText
     */
    public function setImageText(?string $imageText): void
    {
        $this->imageText = $imageText;
    }

    /**
     * @return null|string
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @param null|string $text
     */
    public function setText(?string $text): void
    {
        $this->text = $text;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function createAndSetThumbnailLogo(LifecycleEventArgs $args)
    {
        $changeSet = $args->getEntityManager()->getUnitOfWork()->getEntityChangeSet($this);

        if(array_key_exists("image", $changeSet) || !$this->id){
            $this->resizeAndUpdateImage();
        }

        return true;
    }

    public function resizeAndUpdateImage()
    {
        if(!$this->image){
            $this->image = null;
            $this->imageThumbnail = null;

            return false;
        }

        $this->image = ResizeImageHandler::resizeLogo($this, ResizeImageHandler::ARTICLE_IMAGE_WIDTH, ResizeImageHandler::ARTICLE_IMAGE_HEIGHT);
        $this->imageThumbnail = ResizeImageHandler::resizeLogo($this, ResizeImageHandler::ARTICLE_IMAGE_WIDTH_THUMBNAIL, ResizeImageHandler::ARTICLE_IMAGE_HEIGHT_THUMBNAIL);
    }

    /**
     * @return Article|null
     */
    public function getArticle(): ?Article
    {
        return $this->article;
    }

    /**
     * @param Article|null $article
     */
    public function setArticle(?Article $article): void
    {
        $this->article = $article;
    }
}