<?php

namespace App\Entity\Client;

use App\Entity\Image;
use App\Entity\User;
use App\Handler\ResizeImageHandler;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="seller_data")
 *
 * @ORM\HasLifecycleCallbacks()
 */
class SellerData
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
     * @var SellerCompany|null
     *
     * One SellerData has One SellerCompany.
     * @ORM\OneToOne(targetEntity="SellerCompany", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     */
    private $sellerCompany;

    /**
     * @var Client
     *
     * One SellerData has One Client.
     * @ORM\OneToOne(targetEntity="Client", inversedBy="sellerData")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    private $client;

    /**
     * @var SellerAdvertDetail
     *
     * One SellerData has One SellerAdvertDetail.
     * @ORM\OneToOne(targetEntity="SellerAdvertDetail", mappedBy="sellerData", cascade={"persist", "remove"})
     */
    private $advertDetail;

    /**
     * @var Image|null
     *
     * One Client has One Image.
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Image", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="photo_id", referencedColumnName="id")
     */
    private $photo;

    /**
     * @param Client $client
     * SellerData constructor.
     */
    public function __construct($client)
    {
        $this->client = $client;
        $this->client->addRole(User::ROLE_SELLER);
        $this->sellerCompany = new SellerCompany();
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
     * @return SellerCompany|null
     */
    public function getSellerCompany(): ?SellerCompany
    {
        return $this->sellerCompany;
    }

    /**
     * @param SellerCompany|null $sellerCompany
     */
    public function setSellerCompany(?SellerCompany $sellerCompany): void
    {
        $this->sellerCompany = $sellerCompany;
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @param Client $client
     */
    public function setClient(Client $client): void
    {
        $this->client = $client;
    }

    /**
     * @return SellerAdvertDetail
     */
    public function getAdvertDetail(): SellerAdvertDetail
    {
        return $this->advertDetail;
    }

    /**
     * @param SellerAdvertDetail $advertDetail
     */
    public function setAdvertDetail(SellerAdvertDetail $advertDetail): void
    {
        $this->advertDetail = $advertDetail;
    }

    /**
     * @return Image|null
     */
    public function getPhoto(): ?Image
    {
        return $this->photo;
    }

    /**
     * @param Image|null $photo
     */
    public function setPhoto(?Image $photo): void
    {
        $this->photo = $photo;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function createAndSetThumbnailLogo(LifecycleEventArgs $args)
    {
        $changeSet = $args->getEntityManager()->getUnitOfWork()->getEntityChangeSet($this);

        if(array_key_exists("photo", $changeSet) || !$this->id){
            $this->updateResizePhoto();
        }

        return true;
    }

    public function updateResizePhoto()
    {
        if(!$this->photo || !$this->photo->getImage()){
            return null;
        }

        $this->photo->setImage(ResizeImageHandler::resizeLogo($this, ResizeImageHandler::SELLER_WIDTH, ResizeImageHandler::SELLER_HEIGHT, false));
    }
}