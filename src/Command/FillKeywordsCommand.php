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

class FillKeywordsCommand extends ContainerAwareCommand
{
    /** @var EntityManagerInterface */
    private $em;

    public function configure()
    {
        $this
            ->setName('app:fill:keywords')
            ->setDescription('Fill keywords for brand, model, sparePart');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        /** @var EntityManagerInterface $em */
        $this->em = $container->get('doctrine.orm.default_entity_manager');

        $this->fillKeywords($output);

        $output->writeln("<info>Done</info>");
    }

    private function fillKeywords(OutputInterface $output)
    {
        $this->fillBrands();

        $output->writeln("<info>Brands done!</info>");

        $this->fillModels();

        $output->writeln("<info>Models done!</info>");

        $this->fillSpareParts();

        $output->writeln("<info>Spare parts done!</info>");

        $this->em->flush();
    }

    private function fillBrands()
    {
        $brands = $this->em->getRepository(Brand::class)->findAll();

        /** @var Brand $brand */
        foreach ($brands as $brand){
            $brand->getName() ? $brand->addKeyWord($brand->getName()) : null;
            $brand->getBrandRu() ? $brand->addKeyWord($brand->getBrandRu()) : null;
            $brand->getBrandEn() ? $brand->addKeyWord($brand->getBrandEn()) : null;
            $brand->getUrl() ? $brand->addKeyWord($brand->getUrl()) : null;
            $brand->getUrlConnectBamper() ? $brand->addKeyWord($brand->getUrlConnectBamper()) : null;
        }
    }

    private function fillModels()
    {
        $models = $this->em->getRepository(Model::class)->findAll();

        /** @var Model $model */
        foreach ($models as $model){
            $model->getName() ? $model->addKeyWord($model->getName()) : null;
            $model->getModelEn() ? $model->addKeyWord($model->getModelEn()) : null;
            $model->getModelRu() ? $model->addKeyWord($model->getModelRu()) : null;
            $model->getUrl() ? $model->addKeyWord($model->getUrl()) : null;
            $model->getUrlConnectBamper() ? $model->addKeyWord($model->getUrlConnectBamper()) : null;

            if(($bracketPos = strpos($model->getName(), '(')) !== false){
                $nameBeforeBracket = trim(substr($model->getName(), 0, $bracketPos));

                if($nameBeforeBracket){
                    $model->addKeyWord($nameBeforeBracket);
                }
            }
        }
    }

    private function fillSpareParts()
    {
        $spareParts = $this->em->getRepository(SparePart::class)->findAll();

        /** @var SparePart $sparePart */
        foreach ($spareParts as $sparePart){
            $sparePart->getName() ? $sparePart->addKeyWord($sparePart->getName()) : null;
            $sparePart->getNamePlural() ? $sparePart->addKeyWord($sparePart->getNamePlural()) : null;
            $sparePart->getAlternativeName1() ? $sparePart->addKeyWord($sparePart->getAlternativeName1()) : null;
            $sparePart->getAlternativeName2() ? $sparePart->addKeyWord($sparePart->getAlternativeName2()) : null;
            $sparePart->getAlternativeName3() ? $sparePart->addKeyWord($sparePart->getAlternativeName3()) : null;
            $sparePart->getAlternativeName4() ? $sparePart->addKeyWord($sparePart->getAlternativeName4()) : null;
            $sparePart->getAlternativeName5() ? $sparePart->addKeyWord($sparePart->getAlternativeName5()) : null;
            $sparePart->getUrlConnectBamper() ? $sparePart->addKeyWord($sparePart->getUrlConnectBamper()) : null;

            if(($bracketPos = strpos($sparePart->getName(), '(')) !== false){
                $nameBeforeBracket = trim(substr($sparePart->getName(), 0, $bracketPos));

                if($nameBeforeBracket){
                    $sparePart->addKeyWord($nameBeforeBracket);
                }
            }

            if(($bracketStartPos = strpos($sparePart->getName(), '(')) !== false &&
                ($bracketEndPos = strpos($sparePart->getName(), ')')) !== false &&
                $bracketStartPos !== $bracketEndPos){
                $nameBetweenBrackets = trim(substr($sparePart->getName(), $bracketStartPos + 1,
                    $bracketEndPos - $bracketStartPos - 1));

                if($nameBetweenBrackets){
                    $sparePart->addKeyWord($nameBetweenBrackets);
                }
            }
        }
    }
}