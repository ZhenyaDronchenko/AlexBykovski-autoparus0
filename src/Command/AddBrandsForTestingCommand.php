<?php

namespace App\Command;

use App\Entity\SparePart;
use App\Generator\PasswordGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddBrandsForTestingCommand extends ContainerAwareCommand
{
// command example
    //php bin/console app:user:create Super "+375(11)111-11-11" password ROLE_SUPER_ADMINISTRATOR
    public function configure()
    {
        $this
            ->setName('app:add:brands')
            ->setDescription('Add test brands')
            ->addArgument('count', InputArgument::REQUIRED, 'Count');
    }
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        /** @var EntityManagerInterface $em */
        $em = $container->get('doctrine.orm.default_entity_manager');
        $count = (int)$input->getArgument('count');
        $generator = new PasswordGenerator();

        for($i = 0; $i < $count; $i++){
            $brand = new SparePart();
            $name = $generator->getUniqueCode(8, "ABCDEFGHIKLMNOPQRSTYVWZ");
            $brandEn = $generator->getUniqueCode(8, "ABCDEFGHIKLMNOPQRSTYVWZ");
            $url = $generator->getUniqueCode(8, "abcdeifghijklmnopqrstyz");
            $text = $generator->getUniqueCode(8, "abcdeifghijklmnopqrstyz");
            $brandRu = $generator->getUniqueCode(8, "ABCDEFGHIKLMNOPQRSTYVWZ");
            $logo = "brand/2018/08/5b82fcaeb5c38.jpg";

            $brand->setName($name);
            $brand->setNameAccusative($brandEn);
            $brand->setNameInstrumental($brandRu);
            $brand->setNameGenitive($brandRu);
            $brand->setNamePlural($brandRu);
            $brand->setAlternativeName1($brandEn);
            $brand->setAlternativeName2($brandEn);
            $brand->setAlternativeName3($brandEn);
            $brand->setAlternativeName4($brandEn);
            $brand->setAlternativeName5($brandEn);
            $brand->setUrl($url);
            $brand->setLogo($logo);
            $brand->setText($text);
            $brand->setActive(true);

            $em->persist($brand);
        }

        $em->flush();

        $output->writeln("<info>Done</info>");
    }
}