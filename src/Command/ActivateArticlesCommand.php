<?php

namespace App\Command;

use App\Entity\Advert\CurrencyRate;
use App\Entity\Article\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ActivateArticlesCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('app:activate:articles')
            ->setDescription('Activate articles');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        /** @var Article[] $articles */
        $articles = $em->getRepository(Article::class)->findToActivate();

        foreach ($articles as $article){
            $article->setActivateAt(null);
            $article->setIsActive(true);
        }

        $em->flush();

        $output->writeln("<info>Done</info>");
    }
}