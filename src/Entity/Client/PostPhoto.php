<?php

namespace App\Entity\Client;

use App\Entity\Image;
use App\Handler\ResizeImageHandler;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="post_photo")
 *
 * @ORM\HasLifecycleCallbacks()
 */
class PostPhoto
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
     * @var Image
     *
     * One PostPhoto has One Image.
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Image", cascade={"persist", "remove"})
     *
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id")
     */
    private $image;

    /**
     * @var Image
     *
     * One PostPhoto has One Image.
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Image", cascade={"persist", "remove"})
     *
     * @ORM\JoinColumn(name="image_thumbnail_id", referencedColumnName="id")
     */
    private $imageThumbnail;

    /**
     * @var Post
     *
     * Many PostPhotos have one Post. This is the owning side.
     *
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="postPhotos")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id")
     */
    private $post;

    /**
     * PostPhoto constructor.
     *
     * @param Image $image
     * @param Post $post
     */
    public function __construct(Image $image, Post $post)
    {
        $this->image = $image;
        $this->post = $post;
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
     * @return Image
     */
    public function getImage(): Image
    {
        return $this->image;
    }

    /**
     * @param Image $image
     */
    public function setImage(Image $image): void
    {
        $this->image = $image;
    }

    /**
     * @return Image
     */
    public function getImageThumbnail(): Image
    {
        return $this->imageThumbnail;
    }

    /**
     * @param Image $imageThumbnail
     */
    public function setImageThumbnail(Image $imageThumbnail): void
    {
        $this->imageThumbnail = $imageThumbnail;
    }

    /**
     * @return Post
     */
    public function getPost(): Post
    {
        return $this->post;
    }

    /**
     * @param Post $post
     */
    public function setPost(Post $post): void
    {
        $this->post = $post;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function createAndSetThumbnailLogo(LifecycleEventArgs $args)
    {
        $changeSet = $args->getEntityManager()->getUnitOfWork()->getEntityChangeSet($this);

        if(array_key_exists("image", $changeSet) || !$this->id){
            $this->updateThumbnailLogos();
        }

        return true;
    }

    public function updateThumbnailLogos()
    {
        if(!$this->image){
            $this->imageThumbnail = null;

            return false;
        }

        if(!$this->imageThumbnail && $this->image){
            $this->imageThumbnail = new Image();
            $this->imageThumbnail->setGeoLocation($this->image->getGeoLocation()->copy());
            $this->imageThumbnail->setCreatedAt($this->image->getCreatedAt());
        }

        $this->imageThumbnail->setImage(ResizeImageHandler::resizeLogo($this->getImage()->getImage(), ResizeImageHandler::POST_IMAGE_WIDTH_THUMBNAIL, ResizeImageHandler::POST_IMAGE_HEIGHT_THUMBNAIL));
    }
}