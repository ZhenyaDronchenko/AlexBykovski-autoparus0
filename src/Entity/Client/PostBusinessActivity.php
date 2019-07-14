<?php

namespace App\Entity\Client;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="post_business_activity")
 */
class PostBusinessActivity
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
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $city;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $activity;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $companyName;

    /**
     * @var Post
     *
     * Many PostCar have one Post. This is the owning side.
     *
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="businessActivities")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id")
     */
    private $post;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", options={"default" : true})
     */
    private $active = 1;

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
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return null|string
     */
    public function getActivity(): ?string
    {
        return $this->activity;
    }

    /**
     * @param null|string $activity
     */
    public function setActivity(?string $activity): void
    {
        $this->activity = $activity;
    }

    /**
     * @return null|string
     */
    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    /**
     * @param null|string $companyName
     */
    public function setCompanyName(?string $companyName): void
    {
        $this->companyName = $companyName;
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

    static function getPostActivityByClientActivity(Post $post, $activity)
    {
        $postActivity = new PostBusinessActivity();
        $postActivity->setPost($post);

        $city = $post->getClient()->getSellerData()->getSellerCompany()->getCity();
        $companyName = $post->getClient()->getSellerData()->getSellerCompany()->getCompanyName();

        $postActivity->setCity($city);
        $postActivity->setActivity($activity);
        $postActivity->setCompanyName($companyName);

        return $postActivity;
    }

    public function toArray()
    {
        return [
            "id" => $this->getId(),
            "city" => $this->getCity(),
            "activity" => SellerCompany::$activities[$this->getActivity()],
            "companyName" => $this->getCompanyName(),
        ];
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }
}