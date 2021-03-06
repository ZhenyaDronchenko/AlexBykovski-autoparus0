<?php

namespace App\SiteMap\Provider;

use App\Entity\Brand;
use App\Entity\City;
use App\Entity\Error\CodeOBD2Error;
use App\Entity\Error\TypeOBD2Error;
use App\Entity\Model;
use App\Entity\SparePart;
use App\SiteMap\SiteMapUrlProvider;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SparePartCatalogOBD2AllCititesSiteMapUrlProvider extends BaseSitemapProvider implements SiteMapUrlProvider
{
    public function provideIndex(): array
    {
        $spareParts = $this->em->getRepository(SparePart::class)->findAllUrlsForSiteMap();
        $baseUrl = $this->router->generate("homepage", [], UrlGeneratorInterface::ABSOLUTE_URL);

        $urls = [$baseUrl . 'sitemap1.xml'];

        foreach ($spareParts as $sparePart){
            $urls[] = $baseUrl . 'sitemap_' . $sparePart["url"] . '.xml';
        }

        return $urls;
    }

    public function provideSimple(string $type): array
    {
        switch($type){
            case "1":
                return $this->provideFirstSitemap();
            default:
                return $this->provideSparePartSitemap($type);
        }
    }

    protected function provideFirstSitemap()
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

    protected function provideSparePartSitemap($sparePartUrl)
    {
        $urls = [];
        $absUrlConst = UrlGeneratorInterface::ABSOLUTE_URL;

        $brandUrls = $this->em->getRepository(Brand::class)->findAllUrlsForSiteMap();

        foreach ($brandUrls as $brand){
            $brandUrl = $brand["url"];

            $urls[] = $this->router->generate("show_spare_part_catalog_choice_model",
                ["urlSP" => $sparePartUrl, "urlBrand" => $brandUrl], $absUrlConst);

            $modelUrls = $this->em->getRepository(Model::class)->findAllUrlByBrandUrl($brand["url"]);

            foreach ($modelUrls as $model){
                $parameters = ["urlSP" => $sparePartUrl, "urlBrand" => $brandUrl, "urlModel" => $model["url"]];

                $this->getModelUrlsForSparePartSitemap($urls, $parameters);
            }
        }

        return $urls;
    }

    private function getModelUrlsForSparePartSitemap(&$urls, $parameters)
    {
        $absUrlConst = UrlGeneratorInterface::ABSOLUTE_URL;
        $cities = $this->em->getRepository(City::class)->findAllUrlsForSiteMap();

        $urls[] = $this->router->generate("show_spare_part_catalog_choice_city",
            $parameters, $absUrlConst);

        foreach ($cities as $city){
            $urls[] = $this->router->generate("show_spare_part_catalog_in_stock",
                array_merge($parameters, ["urlCity" => $city["url"]]), $absUrlConst);
        }

        $urls[] = $this->router->generate("show_spare_part_catalog_in_stock",
            array_merge($parameters, ["urlCity" => City::ALL_CITIES]), $absUrlConst);

        $urls[] = $this->router->generate("show_spare_part_catalog_final_page",
            array_merge($parameters, ["urlCity" => City::CAPITAL]), $absUrlConst);
    }
}