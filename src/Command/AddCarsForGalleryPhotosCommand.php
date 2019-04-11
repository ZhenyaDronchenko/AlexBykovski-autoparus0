<?php

namespace App\Command;

use App\Entity\Client\GalleryPhoto;
use App\Entity\Client\GalleryPhotoCar;
use App\Entity\SparePart;
use App\Entity\SparePartCondition;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddCarsForGalleryPhotosCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('app:add:cars-for-gallery-photos')
            ->setDescription('Add cars for gallery photos');
    }
    public function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        $galleryPhotos = $em->getRepository(GalleryPhoto::class)->findAll();

        /** @var GalleryPhoto $galleryPhoto */
        foreach ($galleryPhotos as $galleryPhoto){
            $galleryPhoto->setUserCars();
        }

        $em->flush();

        $output->writeln("<info>Done</info>");
    }
}