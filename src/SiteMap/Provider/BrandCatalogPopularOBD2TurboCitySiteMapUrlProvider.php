<?php

namespace App\SiteMap\Provider;

use App\Entity\Brand;
use App\Entity\City;
use App\Entity\Error\CodeOBD2Error;
use App\Entity\Error\TypeOBD2Error;
use App\Entity\Model;
use App\Entity\SparePart;
use App\SiteMap\SiteMapUrlProvider;
use App\SiteMap\SiteMapFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class BrandCatalogPopularOBD2TurboCitySiteMapUrlProvider implements SiteMapUrlProvider
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var RouterInterface */
    private $router;

    /** @var string $publicPath */
    private $publicPath;

    const TURBO_TYPE = "turbo_";
    const CITY_TYPE = "city_";

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
        $baseUrl = $this->router->generate("homepage", [], UrlGeneratorInterface::ABSOLUTE_URL);

        $urls = [$baseUrl . 'sitemap1.xml', $baseUrl . "sitemap_OBD2.xml"];

        return array_merge($urls, $this->getSitemapBrandCatalogNames(), $this->getSiteMapTurboCatalogNames(),
            $this->getSiteMapCityCatalogNames());
    }

    public function provideSimple(string $type): array
    {
        switch($type){
            case "1":
                return $this->provideSparePart();
            case "OBD2":
                return $this->provideOBD2();
            default:
                if(strpos($type,  self::TURBO_TYPE) === 0){
                    return $this->provideTurbo(str_replace(self::TURBO_TYPE, "", $type));
                }
                elseif (strpos($type,  self::CITY_TYPE) === 0){
                    return $this->provideCity(str_replace(self::CITY_TYPE, "", $type));
                }
                elseif(strpos($type, Brand::TOYOTA_RUS_URL . "_") === 0){
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
        $sparePartUrls = $this->em->getRepository(SparePart::class)->findPopularUrlsForSiteMap();

        foreach ($sparePartUrls as $sparePartUrl){
            $urls[] = $this->router->generate("show_spare_part_catalog_choice_brand", ["url" => $sparePartUrl["url"]], UrlGeneratorInterface::ABSOLUTE_URL);

            foreach ($brandUrls as $brandUrl){
                $urls[] = $this->router->generate("show_spare_part_catalog_choice_model",
                    ["urlSP" => $sparePartUrl["url"], "urlBrand" => $brandUrl["url"]], UrlGeneratorInterface::ABSOLUTE_URL);
            }
        }

        return $urls;
    }

    protected function provideOBD2()
    {
        $types = $this->em->getRepository(TypeOBD2Error::class)->findAllUrlsForSiteMap();
        $absUrlConst = UrlGeneratorInterface::ABSOLUTE_URL;

        $obd2BasePage = $this->router->generate("show_obd2_error_catalog_choice_type", [], $absUrlConst);

        $urls = [$obd2BasePage];

        foreach ($types as $type){
            $urls[] = $this->router->generate("show_obd2_error_catalog_choice_code", ["urlType" => $type["url"]], $absUrlConst);
            $codes = $this->em->getRepository(CodeOBD2Error::class)->findAllUrlsForSiteMap($type["url"]);

            foreach ($codes as $code){
                if(!$code["url"]){
                    continue;
                }

                $parameters = ["urlType" => $type["url"], "urlCode" => $code["url"]];

                $urls[] = $this->router->generate("show_obd2_error_catalog_choice_transcript",
                    $parameters, $absUrlConst);

                $urls[] = $this->router->generate("show_obd2_error_catalog_choice_reason",
                    $parameters, $absUrlConst);
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

    protected function provideTurbo($brandEn)
    {
        $urls = [];
        $brand = $this->em->getRepository(Brand::class)->findOneBy(["brandEn" => $brandEn]);

        if(!($brand instanceof Brand)){
            return $urls;
        }

        $brandUrl = $brand->getUrl();
        $models = $this->em->getRepository(Model::class)->findPopularUrlByBrandUrl($brandUrl);
        $spareParts = $this->em->getRepository(SparePart::class)->findPopularUrlsForSiteMap();
        $capitalRegionalCities = $this->em->getRepository(City::class)->findUrlsForSiteMap([City::CAPITAL_TYPE, City::REGIONAL_CITY_TYPE]);

        $cities = [City::ALL_CITIES];

        foreach ($capitalRegionalCities as $city){
            $cities[] = $city["url"];
        }


        foreach ($models as $model){
            $modelUrl = $model["url"];

            $urls[] = $this->router->generate("turbo_catalog_choice_spare_part",
                ["urlBrand" => $brandUrl, "urlModel" => $modelUrl],
                UrlGeneratorInterface::ABSOLUTE_URL);

            foreach ($spareParts as $sparePart){
                $urls = array_merge($urls, $this->getTurboCatalogSparePartUrls($brandUrl, $modelUrl, $sparePart, $cities));
            }
        }

        return $urls;
    }

    protected function provideCity($cityName)
    {
        $urls = [];
        $city = $this->em->getRepository(City::class)->findOneBy(["name" => $cityName]);

        if(!($city instanceof City)){
            return $urls;
        }

        $cityUrl = $city->getUrl();
        $brands = $this->em->getRepository(Brand::class)->findAllUrlsForSiteMap();

        foreach ($brands as $brand){
            $brandUrl = $brand["url"];

            $urls[] = $this->router->generate("show_city_catalog_choice_model",
                ["urlCity" => $cityUrl, "urlBrand" => $brandUrl],
                UrlGeneratorInterface::ABSOLUTE_URL);

            $models = $this->em->getRepository(Model::class)->findAllByBrandUrl($brandUrl);

            /** @var Model $model */
            foreach ($models as $model){
                $urls = array_merge($urls, $this->getCityCatalogModelsUrls($model, $cityUrl, $brandUrl));
            }
        }

        return $urls;
    }

    protected function getSitemapBrandCatalogNames()
    {
        $brandUrls = $this->em->getRepository(Brand::class)->findPopularUrlsForSiteMap();
        $toyotaRusModelUrls = $this->em->getRepository(Model::class)->findPopularUrlByBrandUrl(Brand::TOYOTA_RUS_URL);
        $baseUrl = $this->router->generate("homepage", [], UrlGeneratorInterface::ABSOLUTE_URL);

        $urls = [];

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

    protected function getSiteMapTurboCatalogNames()
    {
        $brands = $this->em->getRepository(Brand::class)->findPopularUrlsForSiteMap();
        $capitalRegionalCities = $this->em->getRepository(City::class)->findUrlsForSiteMap([City::CAPITAL_TYPE, City::REGIONAL_CITY_TYPE]);
        $baseUrl = $this->router->generate("homepage", [], UrlGeneratorInterface::ABSOLUTE_URL);

        $cities = [City::ALL_CITIES];

        foreach ($capitalRegionalCities as $city){
            $cities[] = $city["url"];
        }

        $urls = [];

        foreach ($brands as $brand){
            $urls[] = $baseUrl . 'sitemap_turbo_' . $brand["brandEn"] . '.xml';
        }

        return $urls;
    }

    protected function getSiteMapCityCatalogNames()
    {
        $capitalRegionalCities = $this->em->getRepository(City::class)->findUrlsForSiteMap([City::CAPITAL_TYPE, City::REGIONAL_CITY_TYPE]);
        $baseUrl = $this->router->generate("homepage", [], UrlGeneratorInterface::ABSOLUTE_URL);

        $urls = [];

        foreach ($capitalRegionalCities as $city){
            $urls[] = $baseUrl . 'sitemap_city_' . $city["name"] . '.xml';
        }

        return $urls;
    }

    private function getTurboCatalogSparePartUrls($brandUrl, $modelUrl, $sparePart, $cities)
    {
        $urls = [];

        $sparePartUrl = $sparePart["url"];

        $urls[] = $this->router->generate("turbo_catalog_choice_city",
            ["urlBrand" => $brandUrl, "urlModel" => $modelUrl, "urlSP" => $sparePartUrl],
            UrlGeneratorInterface::ABSOLUTE_URL);

        foreach ($cities as $city){
            $urls[] = $this->router->generate("turbo_catalog_in_stock",
                ["urlBrand" => $brandUrl, "urlModel" => $modelUrl, "urlSP" => $sparePartUrl, "urlCity" => $city],
                UrlGeneratorInterface::ABSOLUTE_URL);
        }

        return $urls;
    }

    private function getCityCatalogModelsUrls(Model $model, $cityUrl, $brandUrl)
    {
        $urls = [];

        $modelUrl = $model->getUrl();
        $yearFrom = (int)$model->getTechnicalData()->getYearFrom();
        $yearTo = (int)$model->getTechnicalData()->getYearTo();

        $urls[] = $this->router->generate("show_city_catalog_choice_year",
            ["urlCity" => $cityUrl, "urlBrand" => $brandUrl, "urlModel" => $modelUrl],
            UrlGeneratorInterface::ABSOLUTE_URL);

        if($yearFrom > $yearTo){
            return $urls;
        }

        foreach (range($yearFrom, $yearTo) as $year){
            $urls[] = $this->router->generate("show_city_catalog_choice_spare_part",
                ["urlCity" => $cityUrl, "urlBrand" => $brandUrl, "urlModel" => $modelUrl, "year" => $year],
                UrlGeneratorInterface::ABSOLUTE_URL);
        }

        return $urls;
    }
}