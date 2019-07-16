<?php

namespace App\Entity\UserData;

use App\Entity\Client\SellerAdvertDetail;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="import_advert_file")
 */
class ImportAdvertFile
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
    private $path;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $countLines;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $countSuccess;

    /**
     * @var SellerAdvertDetail
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Client\SellerAdvertDetail", inversedBy="importSpecificAdvertFiles")
     * @ORM\JoinColumn(name="seller_advert_detail_id", referencedColumnName="id")
     */
    private $sellerAdvertDetail;

    /**
     * ImportAdvertFile constructor.
     * @param string $path
     * @param int $countLines
     * @param int $countSuccess
     * @param SellerAdvertDetail $sellerAdvertDetail
     */
    public function __construct(string $path, int $countLines, int $countSuccess, SellerAdvertDetail $sellerAdvertDetail)
    {
        $this->path = $path;
        $this->countLines = $countLines;
        $this->countSuccess = $countSuccess;
        $this->sellerAdvertDetail = $sellerAdvertDetail;

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
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path): void
    {
        $this->path = $path;
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
    public function getCountLines(): int
    {
        return $this->countLines;
    }

    /**
     * @param int $countLines
     */
    public function setCountLines(int $countLines): void
    {
        $this->countLines = $countLines;
    }

    /**
     * @return int
     */
    public function getCountSuccess(): int
    {
        return $this->countSuccess;
    }

    /**
     * @param int $countSuccess
     */
    public function setCountSuccess(int $countSuccess): void
    {
        $this->countSuccess = $countSuccess;
    }

    /**
     * @return SellerAdvertDetail
     */
    public function getSellerAdvertDetail(): SellerAdvertDetail
    {
        return $this->sellerAdvertDetail;
    }

    /**
     * @param SellerAdvertDetail $sellerAdvertDetail
     */
    public function setSellerAdvertDetail(SellerAdvertDetail $sellerAdvertDetail): void
    {
        $this->sellerAdvertDetail = $sellerAdvertDetail;
    }

    public function getClient()
    {
        return $this->sellerAdvertDetail->getSellerData()->getClient();
    }
}