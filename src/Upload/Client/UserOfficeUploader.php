<?php

namespace App\Upload\Client;

//https://packagist.org/packages/gumlet/php-image-resize
use App\Entity\Client\Client;
use App\Entity\Image;
use App\Provider\GeoLocation\GeoLocationProvider;
use Doctrine\ORM\EntityManagerInterface;
use Gumlet\ImageResize;

class UserOfficeUploader
{
    /** @var GeoLocationProvider */
    private $geoLocationProvider;

    /** @var EntityManagerInterface */
    private $em;

    /**
     * UserOfficeUploader constructor.
     * @param GeoLocationProvider $geoLocationProvider
     * @param EntityManagerInterface $em
     */
    public function __construct(GeoLocationProvider $geoLocationProvider, EntityManagerInterface $em)
    {
        $this->geoLocationProvider = $geoLocationProvider;
        $this->em = $em;
    }

    /**
     * @param $file
     * @param $coordinates
     * @param $ip
     * @param $uploadDir
     * @param Client $client
     *
     * @throws \Gumlet\ImageResizeException
     */
    public function uploadImagesPersonOffice($path, $coordinates, $ip, $uploadDir, Client $client)
    {
        $image = $this->resizeImagePersonOffice($uploadDir, $path,
            Image::USER_IMAGE_WIDTH, Image::USER_IMAGE_HEIGHT);
        $this->setImageGeoLocation($image, $coordinates, $ip);

        $imageThumbnail = $this->resizeImagePersonOffice($uploadDir, $path,
            Image::USER_THUMBNAIL_IMAGE_WIDTH, Image::USER_THUMBNAIL_IMAGE_HEIGHT, true);
        $this->setImageGeoLocation($imageThumbnail, $coordinates, $ip);

        $client->setPhoto($image);
        $client->setThumbnailPhoto($imageThumbnail);

        $this->em->flush();
    }

    /**
     * @param string $uploadDir
     * @param string $path
     * @param string $width
     * @param string $height
     * @param $isThumbnail $height
     *
     * @return Image
     *
     * @throws \Gumlet\ImageResizeException
     */
    public function resizeImagePersonOffice($uploadDir, $path, $width, $height, $isThumbnail = false)
    {
        $fileFullPath = $uploadDir . '/' . $path;
        $imageResizer = new ImageResize($fileFullPath);
        $imageResizer->resize($width, $height, true);

        $savePath = $isThumbnail ? UserOfficeUploader::getThumbnailPath($path) : $path;

        $imageResizer->save($uploadDir . '/' .  $savePath);

        return new Image($savePath);
    }

    public function setImageGeoLocation(Image &$image, $coordinates, $ip)
    {
        $geoLocation = $this->geoLocationProvider->addGeoLocationToImage($coordinates, $ip);

        $image->setGeoLocation($geoLocation);

        return true;
    }

    static function getThumbnailPath($path)
    {
        $fileInfo = pathinfo($path);

        return $fileInfo['dirname'] . '/' . $fileInfo["filename"] . "_thumbnail." . $fileInfo['extension'];
    }
}