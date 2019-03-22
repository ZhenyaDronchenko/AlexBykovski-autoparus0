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

class BrandCatalogPopularSiteMapUrlProvider implements SiteMapUrlProvider
{
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
        $brandUrls = $this->em->getRepository(Brand::class)->findPopularUrlsForSiteMap();
        $toyotaRusModelUrls = $this->em->getRepository(Model::class)->findPopularUrlByBrandUrl(Brand::TOYOTA_RUS_URL);
        $baseUrl = $this->router->generate("homepage", [], UrlGeneratorInterface::ABSOLUTE_URL);

        $urls = [$baseUrl . 'sitemap1.xml'];

        foreach ($brandUrls as $brandUrl){
            if($brandUrl["url"] === Brand::TOYOTA_RUS_URL){
                continue;
            }

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
                if(strpos($type, Brand::TOYOTA_RUS_URL . "_") === 0){
                    $model = strlen($type) > strlen(Brand::TOYOTA_RUS_URL . "_") ? substr($type, strlen(Brand::TOYOTA_RUS_URL . "_")) : "";

                    return $this->provideToyotaRusModel($model);
                }

                return $this->provideBrand($type);
        }
    }

    protected function provideSparePart()
    {
        $urls = [];
        $brandUrls = $this->em->getRepository(Brand::class)->findPopularUrlsForSiteMap();
        $sparePartUrls = $this->em->getRepository(SparePart::class)->findPopularUrlByBrandUrl();

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
        $brandUrl = Brand::TOYOTA_RUS_URL;

        $model = $this->em->getRepository(Model::class)->findOneBy(["url" => $modelUrl]);

        if(!($model instanceof Model) || $model->getBrand()->getUrl() !== $brandUrl || !$model->isPopular()){
            return $urls;
        }

        $sparePartUrls = $this->em->getRepository(SparePart::class)->findAllUrlsForSiteMap();

        $urls[] = $this->router->generate("show_brand_catalog_choice_spare_part",
            ["urlBrand" => $brandUrl, "urlModel" => $modelUrl], UrlGeneratorInterface::ABSOLUTE_URL);

        foreach ($sparePartUrls as $sparePartUrl){
            $urls[] = $this->router->generate("show_brand_catalog_choice_city",
                ["urlBrand" => $brandUrl, "urlModel" => $modelUrl, "urlSP" => $sparePartUrl["url"]],
                UrlGeneratorInterface::ABSOLUTE_URL);

            $urls[] = $this->router->generate("show_brand_catalog_in_stock",
                ["urlBrand" => $brandUrl, "urlModel" => $modelUrl, "urlSP" => $sparePartUrl["url"],
                    "urlCity" => City::CAPITAL],
                UrlGeneratorInterface::ABSOLUTE_URL);

            $urls[] = $this->router->generate("show_brand_catalog_final_page",
                ["urlBrand" => $brandUrl, "urlModel" => $modelUrl, "urlSP" => $sparePartUrl["url"],
                    "urlCity" => City::CAPITAL],
                UrlGeneratorInterface::ABSOLUTE_URL);
        }

        return $urls;
    }

    protected function provideBrand($brandUrl)
    {
        $urls = [];
        $brand = $this->em->getRepository(Brand::class)->findOneBy(["url" => $brandUrl]);

        if($brandUrl === Brand::TOYOTA_RUS_URL || !($brand instanceof Brand) || !$brand->isPopular()){
            return $urls;
        }

        $modelUrls = $this->em->getRepository(Model::class)->findPopularUrlByBrandUrl($brandUrl);
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