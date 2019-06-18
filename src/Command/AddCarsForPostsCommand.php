<?php

namespace App\Command;

use App\Entity\Client\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddCarsForPostsCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('app:add:cars-for-posts')
            ->setDescription('Add cars for posts');
    }
    public function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        $posts = $em->getRepository(Post::class)->findAll();

        /** @var Post $post */
        foreach ($posts as $post){
            $post->setUserCars();
        }

        $em->flush();

        $output->writeln("<info>Done</info>");
    }
}