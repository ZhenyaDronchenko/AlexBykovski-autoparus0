<?php

namespace App\SiteMap\Provider;

use App\Entity\Brand;
use App\Entity\City;
use App\Entity\Model;
use App\Entity\SparePart;
use App\SiteMap\SiteMapUrlProvider;
use App\SiteMap\SiteMapFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class BrandCatalogSiteMapUrlProvider implements SiteMapUrlProvider
{
    const INDICATOR_SPARE_PART = "1";
    const TOYOTA_RUS = "toyota_rus";
    const INDEX_FILE_NAME = "sitemap_index.xml";

    /** @var EntityManagerInterface */
    private $em;

    /** @var RouterInterface */
    private $router;

    /** @var string $publicPath */
    private $publicPath;

    /**
     * BrandCatalogSiteMapBuilder constructor.
     *
     * @param EntityManagerInterface $em
     * @param RouterInterface $router
     * @param string $publicPath
     */
    public function __construct(EntityManagerInterface $em, RouterInterface $router, string $publicPath)
    {
        $this->em = $em;
        $this->router = $router;
        $this->publicPath = $publicPath;
    }


    public function provide(string $requestFile): array
    {

        $type = strlen($requestFile) && $requestFile[0] === '_' ? substr($requestFile, 1) : $requestFile;

        switch ($type){
            case SiteMapFactory::SITE_MAP_INDEX:
                return $this->provideIndex();
            default:
                return $this->provideSimple($type);
        }
    }

    public function provideIndex(): array
    {
        $brandUrls = $this->em->getRepository(Brand::class)->findAllUrlsForSiteMap();
        $toyotaRusModelUrls = $this->em->getRepository(Model::class)->findAllUrlByBrandUrl(Brand::TOYOTA_RUS_URL);
        $baseUrl = $this->router->generate("homepage", [], UrlGeneratorInterface::ABSOLUTE_URL);

        $urls = [$baseUrl . 'sitemap1.xml'];

        foreach ($brandUrls as $brandUrl){
            $urls[] = $baseUrl . 'sitemap_' . $brandUrl["url"] . '.xml';
        }

        foreach ($toyotaRusModelUrls as $modelUrl){
            $urls[] = $baseUrl . 'sitemap_' . Brand::TOYOTA_RUS_URL . '_' . $modelUrl["url"] . '.xml';
        }

        return $urls;
    }

    public function provideSimple(string $type): array
    {
        switch($type){
            case "1":
                return $this->provideSparePart();
            default:
                if(strpos($type, self::TOYOTA_RUS . "_") === 0){
                    $model = strlen($type) > strlen(self::TOYOTA_RUS . "_") ? substr($type, strlen(self::TOYOTA_RUS . "_")) : "";

                    return $this->provideToyotaRusModel($model);
                }

                return $this->provideBrand($type);
        }
    }

    protected function provideSparePart()
    {
        $urls = [];
        $brandUrls = $this->em->getRepository(Brand::class)->findAllUrlsForSiteMap();
        $sparePartUrls = $this->em->getRepository(SparePart::class)->findAllUrlsForSiteMap();

        foreach ($sparePartUrls as $sparePartUrl){
            $urls[] = $this->router->generate("show_spare_part_catalog_choice_brand", ["url" => $sparePartUrl["url"]], UrlGeneratorInterface::ABSOLUTE_URL);

            foreach ($brandUrls as $brandUrl){
                $urls[] = $this->router->generate("show_spare_part_catalog_choice_model",
                    ["urlSP" => $sparePartUrl["url"], "urlBrand" => $brandUrl["url"]], UrlGeneratorInterface::ABSOLUTE_URL);
            }
        }

        return $urls;
    }

    protected function provideToyotaRusModel($modelUrl)
    {
        $urls = [];
        $brandUrl = self::TOYOTA_RUS;

        $model = $this->em->getRepository(Model::class)->findOneBy(["url" => $modelUrl]);

        if(!($model instanceof Model) || $model->getBrand()->getUrl() !== $brandUrl){
            return $urls;
        }

        $sparePartUrls = $this->em->getRepository(SparePart::class)->findAllUrlsForSiteMap();
        $cityUrls = $this->em->getRepository(City::class)->findAllUrlsForSiteMap();
        $cityUrls[] = ["url" => City::ALL_CITIES];

        $urls[] = $this->router->generate("show_brand_catalog_choice_spare_part",
            ["urlBrand" => $brandUrl, "urlModel" => $modelUrl], UrlGeneratorInterface::ABSOLUTE_URL);

        foreach ($sparePartUrls as $sparePartUrl){
            $urls[] = $this->router->generate("show_brand_catalog_choice_city",
                ["urlBrand" => $brandUrl, "urlModel" => $modelUrl, "urlSP" => $sparePartUrl["url"]],
                UrlGeneratorInterface::ABSOLUTE_URL);

            foreach ($cityUrls as $cityUrl){
                $urls[] = $this->router->generate("show_brand_catalog_in_stock",
                    ["urlBrand" => $brandUrl, "urlModel" => $modelUrl, "urlSP" => $sparePartUrl["url"],
                        "urlCity" => $cityUrl["url"]],
                    UrlGeneratorInterface::ABSOLUTE_URL);

                $urls[] = $this->router->generate("show_brand_catalog_final_page",
                    ["urlBrand" => $brandUrl, "urlModel" => $modelUrl, "urlSP" => $sparePartUrl["url"],
                        "urlCity" => $cityUrl["url"]],
                    UrlGeneratorInterface::ABSOLUTE_URL);
            }
        }

        return $urls;
    }

    protected function provideBrand($brandUrl)
    {
        $urls = [];

        if($brandUrl === Brand::TOYOTA_RUS_URL){
            return $urls;
        }

        $modelUrls = $this->em->getRepository(Model::class)->findAllUrlByBrandUrl($brandUrl);
        $sparePartUrls = $this->em->getRepository(SparePart::class)->findAllUrlsForSiteMap();

        if(count($modelUrls)){
            $urls[] = $this->router->generate("show_brand_catalog_choice_model", ["urlBrand" => $brandUrl], UrlGeneratorInterface::ABSOLUTE_URL);
        }

        foreach ($modelUrls as $modelUrl){
            $urls[] = $this->router->generate("show_brand_catalog_choice_spare_part",
                ["urlBrand" => $brandUrl, "urlModel" => $modelUrl["url"]],
                UrlGeneratorInterface::ABSOLUTE_URL);

            foreach ($sparePartUrls as $sparePartUrl){
                $urls[] = $this->router->generate("show_brand_catalog_choice_city",
                    ["urlBrand" => $brandUrl, "urlModel" => $modelUrl["url"], "urlSP" => $sparePartUrl["url"]],
                    UrlGeneratorInterface::ABSOLUTE_URL);
            }
        }

        return $urls;
    }
}