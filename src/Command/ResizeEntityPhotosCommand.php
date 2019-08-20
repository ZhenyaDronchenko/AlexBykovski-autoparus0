<?php

namespace App\Command;

use App\Entity\Client\Client;
use App\Entity\Client\SellerData;
use App\Entity\Image;
use App\Handler\ResizeImageHandler;
use App\Upload\Client\UserOfficeUploader;
use Doctrine\ORM\EntityManagerInterface;
use Gumlet\ImageResize;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ResizeEntityPhotosCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('app:change:resize-entity-photos')
            ->setDescription('Resize entity photos');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        /** @var UserOfficeUploader $resizer */
        $resizer = $this->getContainer()->get("app.upload.client.user_office_upload");
        $uploadDir = $this->getContainer()->getParameter("upload_directory");

        $sellerData = $em->getRepository(SellerData::class)->findAll();

        /** @var SellerData $datum */
        foreach ($sellerData as $datum){

            if(!$datum->getPhoto() || !($image = $datum->getPhoto()->getImage()) || !file_exists($uploadDir . '/' . $image) ||
                pathinfo($datum->getPhoto()->getImage(), PATHINFO_EXTENSION) === "webp") {
                continue;
            }

            $datum->updateResizePhoto();
        }

        $em->flush();

        $output->writeln("<info>Done</info>");
    }
}