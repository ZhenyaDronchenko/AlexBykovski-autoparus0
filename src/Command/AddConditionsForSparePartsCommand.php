<?php

namespace App\Command;

use App\Entity\SparePart;
use App\Entity\SparePartCondition;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddConditionsForSparePartsCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('app:add:conditions-to-spare-parts')
            ->setDescription('Add conditions');
    }
    public function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        $spareParts = $em->getRepository(SparePart::class)->findAll();

        /** @var SparePart $sparePart */
        foreach ($spareParts as $sparePart){
            $conditions = $sparePart->createDefaultConditions();

            /** @var SparePartCondition $condition */
            foreach ($conditions as $condition){
                $em->persist($condition);
            }
        }

        $em->flush();

        $output->writeln("<info>Done</info>");
    }
}