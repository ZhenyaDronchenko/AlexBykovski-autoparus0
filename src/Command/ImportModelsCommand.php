<?php

namespace App\Command;


use App\Entity\Admin;
use App\Entity\Brand;
use App\Entity\Buyer;
use App\Entity\Model;
use App\Entity\Seller;
use App\Entity\SparePart;
use App\Entity\User;
use App\Generator\PasswordGenerator;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use League\Csv\Statement;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportModelsCommand extends ContainerAwareCommand
{
    const BRANDS_FILE_PATH = "/public/import/brands.csv";
    const MODELS_FILE_PATH = "/public/import/models.csv";
    const IMAGE_URL = "https://d4d.by/mini_img.php?src=/images/logo/%s&w=300&h=200&zc=2&q=100";
    const SYMBOLS_FOR_NAME_FILE = "abcdeifghijklmnopqrstyz0123456789";

    static $needBrandKeys = ["id", "name"];
    static $needModelsKeys = ["id", "name", "name_en", "name_ru", "url", "start_year", "end_year", "logo", "text2", "marka"];

    public function configure()
    {
        $this
            ->setName('app:import:models')
            ->setDescription('Import models');
    }
    public function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set("memory_limit", -1);

        $container = $this->getContainer();
        $rootDir = $container->getParameter("kernel.project_dir");

        $brandsFile = Reader::createFromPath($rootDir . self::BRANDS_FILE_PATH, 'r');
        $brandsCsv = (new Statement())->process($brandsFile)->jsonSerialize();

        $modelsFile = Reader::createFromPath($rootDir . self::MODELS_FILE_PATH, 'r');
        $modelsCsv = (new Statement())->process($modelsFile)->jsonSerialize();

        if(count($brandsCsv) < 2 || count($modelsCsv) < 2){
            $output->writeln("<error>Files empty!</error>");

            return false;
        }

        $brands = $this->parseBrands($brandsCsv, $modelsCsv);

        $brands = $this->uploadFiles($brands);

        $this->saveModels($brands);

        $output->writeln("<info>Done</info>");
    }

    protected function parseBrands($brandsCsv, $modelsCsv)
    {
        $brandsKeys = array_filter($brandsCsv[0], function ($elem){
            return in_array($elem, self::$needBrandKeys);
        });

        $modelsKeys = array_filter($modelsCsv[0], function ($elem){
            return in_array($elem, self::$needModelsKeys);
        });

        $brandIdKey = array_flip($brandsKeys)["id"];
        $modelBrandIdKey = array_flip($modelsKeys)["marka"];

        $brands = [];

        foreach ($brandsCsv as $key => $brandCsv){
            if($key == 0){
                continue;
            }

            $brandId = $brandCsv[$brandIdKey];
            $brands[$brandId] = ["models" => []];

            foreach ($brandsKeys as $brandKey => $brandField){
                $brands[$brandId][$brandField] = $brandCsv[$brandKey];
            }
        }

        foreach ($modelsCsv as $key => $modelCsv){
            if($key == 0){
                continue;
            }

            $brandId = $modelCsv[$modelBrandIdKey];
            $model = [];

            foreach ($modelsKeys as $modelKey => $modelField){
                $model[$modelField] = $modelCsv[$modelKey];
            }

            $brands[$brandId]["models"][] = $model;
        }

        return array_filter($brands, function($brand){
            return count($brand["models"]) > 0;
        });
    }

    protected function uploadFiles($brands)
    {
        $rootDir = $this->getContainer()->getParameter("kernel.project_dir") . "/public/images/";
        $relativePathFolder = "model/import/";
        $generator = new PasswordGenerator();

        if (!file_exists($rootDir . $relativePathFolder)) {
            mkdir($rootDir . $relativePathFolder, 0777, true);
        }

        foreach ($brands as $keyBrand => $brand) {
            foreach ($brand["models"] as $key => $model) {
                $logo = $model["logo"];

                if(!$logo){
                    continue;
                }

                $extension = pathinfo($logo)['extension'];

                $relativePathImage = $relativePathFolder . $generator->getUniqueCode(10, self::SYMBOLS_FOR_NAME_FILE) . '.' .  $extension;
                $url = sprintf(self::IMAGE_URL, $logo);

                file_put_contents($rootDir . $relativePathImage, file_get_contents($url));

                $brands[$keyBrand]["models"][$key]["logo"] = $relativePathImage;
            }
        }

        return $brands;
    }

    protected function saveModels(array $brands)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        foreach ($brands as $keyBrand => $brandArray) {
            $brand = $em->getRepository(Brand::class)->findOneBy(["name" => $brandArray["name"]]);

            if(!($brand instanceof Brand)){
                echo "Not found brand: " . $brandArray["name"] . PHP_EOL;

                continue;
            }

            foreach ($brandArray["models"] as $key => $modelArray) {
                $model = $em->getRepository(Model::class)->findOneBy(["name" => $modelArray["name"]]);

                if(!($model instanceof Model)){
//                    $model = new Model();
                    continue;
                }

//                $model->setName($modelArray["name"]);
//                $model->setModelEn($modelArray["name_en"]);
//                $model->setModelRu($modelArray["name_ru"]);
//                $model->setUrl($modelArray["url"]);
                $model->setLogo($modelArray["logo"]);
//                $model->setText($modelArray["text2"]);
//                $model->getTechnicalData()->setYearFrom($modelArray["start_year"]);
//                $model->getTechnicalData()->setYearTo($modelArray["end_year"]);
//                $model->setText($modelArray["text2"]);
//                $model->setBrand($brand);
//                $model->setActive(true);

                //$em->persist($model);
            }
        }

        $em->flush();
    }
}