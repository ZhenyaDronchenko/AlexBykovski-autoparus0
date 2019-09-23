<?php

namespace App\Entity\Article;

use App\Entity\User;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Article\ArticleRepository")
 *
 * @ORM\Table(name="article")
 */
class Article
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
     * @Assert\NotBlank(message="Обязательное поле")
     *
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Обязательное поле")
     *
     * @ORM\Column(type="string")
     */
    private $description;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Обязательное поле")
     *
     * @ORM\Column(type="string")
     */
    private $headline1;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Обязательное поле")
     *
     * @ORM\Column(type="string")
     */
    private $headline2;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $author;

    /**
     * @var ArticleImage
     *
     * One Article has One ArticleImage.
     * @ORM\OneToOne(targetEntity="ArticleImage", cascade={"persist"}, orphanRemoval=true)
     * @ORM\JoinColumn(name="main_image_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $mainArticleImage;

    /**
     * @var Collection
     *
     * One product has many features. This is the inverse side.
     * @ORM\OneToMany(targetEntity="ArticleImage", mappedBy="article", cascade={"persist"}, orphanRemoval=true)
     */
    private $articleImages;

    /**
     * @var Collection
     *
     * One Article has many ArticleBanners. This is the inverse side.
     * @ORM\OneToMany(targetEntity="ArticleBanner", mappedBy="article", cascade={"persist"}, orphanRemoval=true)
     */
    private $banners;

    /**
     * @var ArticleDetail
     *
     * One Article has One ArticleDetail.
     * @ORM\OneToOne(targetEntity="ArticleDetail", mappedBy="article", cascade={"persist", "remove"})
     */
    private $detail;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $isActive;

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
     * @var integer
     *
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $views;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $directViews;

    /**
     * @var User|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="creator_id", referencedColumnName="id")
     */
    private $creator;

    /**
     * @var Collection
     *
     * One product has many features. This is the inverse side.
     * @ORM\OneToMany(targetEntity="ArticleComment", mappedBy="article")
     */
    private $comments;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $activateAt;

    /**
     * Article constructor.
     */
    public function __construct()
    {
        $this->articleImages = new ArrayCollection();
        $this->banners = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->detail = new ArticleDetail();
        $this->mainArticleImage = new ArticleImage();

        $this->detail->setArticle($this);

        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
        $this->activateAt = new DateTime();

        $this->title = "";
        $this->description = "";
        $this->headline1 = "";
        $this->headline2 = "";

        $this->isActive = false;
        $this->views = 0;
        $this->directViews = 0;
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
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getHeadline1(): string
    {
        return $this->headline1;
    }

    /**
     * @param string $headline1
     */
    public function setHeadline1(string $headline1): void
    {
        $this->headline1 = $headline1;
    }

    /**
     * @return string
     */
    public function getHeadline2(): string
    {
        return $this->headline2;
    }

    /**
     * @param string $headline2
     */
    public function setHeadline2(string $headline2): void
    {
        $this->headline2 = $headline2;
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
     * @return ArticleImage
     */
    public function getMainArticleImage(): ArticleImage
    {
        return $this->mainArticleImage;
    }

    /**
     * @param ArticleImage $mainArticleImage
     */
    public function setMainArticleImage(ArticleImage $mainArticleImage): void
    {
        $this->mainArticleImage = $mainArticleImage;
    }

    /**
     * @return Collection
     */
    public function getArticleImages(): Collection
    {
        return $this->articleImages;
    }

    /**
     * @param Collection $articleImages
     */
    public function setArticleImages(Collection $articleImages): void
    {
        $this->articleImages = $articleImages;
    }

    /**
     * @return Collection
     */
    public function getBanners(): Collection
    {
        return $this->banners;
    }

    /**
     * @param Collection $banners
     */
    public function setBanners(Collection $banners): void
    {
        $this->banners = $banners;
    }

    /**
     * @return ArticleDetail
     */
    public function getDetail(): ArticleDetail
    {
        return $this->detail;
    }

    /**
     * @param ArticleDetail $detail
     */
    public function setDetail(ArticleDetail $detail): void
    {
        $this->detail = $detail;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     */
    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
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

    /**
     * @return int
     */
    public function getViews(): int
    {
        return $this->views;
    }

    /**
     * @param int $views
     */
    public function setViews(int $views): void
    {
        $this->views = $views;
    }

    /**
     * @return int
     */
    public function getDirectViews(): int
    {
        return $this->directViews;
    }

    /**
     * @param int $directViews
     */
    public function setDirectViews(int $directViews): void
    {
        $this->directViews = $directViews;
    }

    /**
     * @return User|null
     */
    public function getCreator(): ?User
    {
        return $this->creator;
    }

    /**
     * @param User|null $creator
     */
    public function setCreator(?User $creator): void
    {
        $this->creator = $creator;
    }

    /**
     * @return Collection
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    /**
     * @return DateTime|null
     */
    public function getActivateAt(): ?DateTime
    {
        return $this->activateAt;
    }

    /**
     * @param DateTime|null $activateAt
     */
    public function setActivateAt(?DateTime $activateAt): void
    {
        $this->activateAt = $activateAt;
    }

    /**
     * @param Collection $types
     */
    public function setTypes(Collection $types): void
    {
        $this->types = $types;
    }

    public function toSearchArray()
    {
        $themes = [];

        if($this->detail->getThemes()->count()){
            /** @var ArticleTheme $theme */
            foreach ($this->detail->getThemes() as $theme){
                $themes[] = [
                    "name" => $theme->getTheme(),
                    "url" => $theme->getUrl(),
                ];
            }
        }

        if(!count($themes)){
            return null;
        }

        return [
            "id" => $this->id,
            "headline1" => $this->headline1,
            "headline2" => $this->headline2,
            "mainImage" => [
                "thumbImage" => $this->mainArticleImage->getImageThumbnail() ?
                    '/images/' . $this->mainArticleImage->getImageThumbnail() : "",
                "text" => $this->mainArticleImage->getText(),
            ],
            "updatedAt" => [
                "date" => $this->updatedAt->format("d-m-Y"),
                "time" => $this->updatedAt->format("H:i"),
            ],
            "themes" => $themes,
        ];
    }
}