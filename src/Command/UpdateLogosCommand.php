<?php

namespace App\Command;

use App\Entity\Brand;
use App\Entity\Model;
use App\Entity\SparePart;
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

        //$this->updateModels();
        //$this->updateSpareParts();
        $this->updateBrands();

        $output->writeln("<info>Done</info>");
    }

    private function updateModels()
    {
        $models = $this->em->getRepository(Model::class)->findAll();

        /** @var Model $model */
        foreach ($models as $model){
            $model->updateThumbnailLogo();
        }

        $this->em->flush();
    }

    private function updateSpareParts()
    {
        $spareParts = $this->em->getRepository(SparePart::class)->findAll();

        /** @var SparePart $sparePart */
        foreach ($spareParts as $sparePart){
            $sparePart->updateThumbnailLogo();
        }

        $this->em->flush();
    }

    private function updateBrands()
    {
        $brands = $this->em->getRepository(Brand::class)->findAll();

        /** @var Brand $brand */
        foreach ($brands as $brand){
            $brand->updateThumbnailLogos();
        }

        $this->em->flush();
    }
}