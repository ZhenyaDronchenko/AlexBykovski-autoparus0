<?php

namespace App\Command;

use App\Entity\Client\Client;
use App\Entity\Image;
use App\Upload\Client\UserOfficeUploader;
use Doctrine\ORM\EntityManagerInterface;
use Gumlet\ImageResize;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ResizeUserPhotosCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('app:change:resize-user-photos')
            ->setDescription('Resize user photos');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        /** @var UserOfficeUploader $resizer */
        $resizer = $this->getContainer()->get("app.upload.client.user_office_upload");
        $uploadDir = $this->getContainer()->getParameter("upload_directory");

        $clients = $em->getRepository(Client::class)->findAll();

        /** @var Client $client */
        foreach ($clients as $client){
            if(!$client->getPhoto() || !file_exists($uploadDir . '/' . $client->getPhoto()->getImage())) {
                continue;
            }

            $image = $client->getPhoto();
            $fileFullPath = $uploadDir . '/' . $image->getImage();
            $imageResizer = new ImageResize($fileFullPath);
            $imageResizer->resize(Image::USER_IMAGE_WIDTH, Image::USER_IMAGE_HEIGHT, true);

            $imageResizer->save($fileFullPath);

            $geolocation = $image->getGeoLocation();
            $coordinates = [
                "longitude" => $geolocation ? $geolocation->getLongitude() : "",
                "latitude" => $geolocation ? $geolocation->getLatitude() : "",
            ];
            //create thumbnail
            $imageThumbnail = $resizer->resizeImagePersonOffice($uploadDir, $image->getImage(),
                Image::USER_THUMBNAIL_IMAGE_WIDTH, Image::USER_THUMBNAIL_IMAGE_HEIGHT, true);
            $resizer->setImageGeoLocation($imageThumbnail, $coordinates, $geolocation->getIp());

            $client->setThumbnailPhoto($imageThumbnail);
        }

        $em->flush();

        $output->writeln("<info>Done</info>");
    }
}