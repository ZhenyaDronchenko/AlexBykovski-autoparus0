<?php

namespace App\Command;

use App\Entity\Brand;
use App\Entity\Client\PostPhoto;
use App\Entity\GeoLocation;
use App\Entity\Image;
use App\Entity\Model;
use App\Entity\SparePart;
use App\Handler\ResizeImageHandler;
use App\Kernel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateLogosCommand extends ContainerAwareCommand
{
    /** @var EntityManagerInterface */
    private $em;

    public function configure()
    {
        $this
            ->setName('app:update:logos')
            ->setDescription('Update logos');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        /** @var EntityManagerInterface $em */
        $this->em = $container->get('doctrine.orm.default_entity_manager');

        $this->updatePostsPhotos();

        $output->writeln("<info>Done</info>");
    }

    private function updatePostsPhotos()
    {
        $postPhotos = $this->em->getRepository(PostPhoto::class)->findAll();

        /** @var PostPhoto $postPhoto */
        foreach ($postPhotos as $postPhoto){
            $baseImage = $postPhoto->getImage();
            $newIMage = new Image($baseImage->getImage());
            $newIMage->setCreatedAt($baseImage->getCreatedAt());
            $newIMage->setGeoLocation($baseImage->getGeoLocation()->copy());

            $this->em->persist($newIMage);

            $postPhoto->setImageThumbnail($newIMage);
        }

        $this->em->flush();
    }
}