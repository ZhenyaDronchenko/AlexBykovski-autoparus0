<?php

namespace App\SiteMap\Provider;

use App\Entity\Brand;
use App\Entity\City;
use App\Entity\Model;
use App\Entity\SparePart;
use App\SiteMap\SiteMapUrlProvider;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UniversalProductGeneralPagesSiteMapUrlProvider extends BaseSitemapProvider implements SiteMapUrlProvider
{
    public function provideIndex(): array
    {
        $brandUrls = $this->em->getRepository(Brand::class)->findAllUrlsForSiteMap(false);
        $baseUrl = $this->router->generate("homepage", [], UrlGeneratorInterface::ABSOLUTE_URL);

        $urls = [];

        foreach ($brandUrls as $brandUrl){
            $modelUrls = $this->em->getRepository(Model::class)->findAllUrlByBrandUrl($brandUrl["url"], false);

            foreach ($modelUrls as $modelUrl) {
                $urls[] = $baseUrl . 'sitemap_' . $brandUrl["url"] . '__' . $modelUrl["url"] . '.xml';
            }
        }

        return $urls;
    }

    public function provideSimple(string $type): array
    {
        return $this->provideSparePartSitemap($type);
    }

    protected function provideSparePartSitemap($brandModelUrl)
    {
        list($brandUrl, $modelUrl) = explode("__", $brandModelUrl);

        $urls = [];

        if(!$brandUrl || !$modelUrl){
            return $urls;
        }

        $spareParts = $this->em->getRepository(SparePart::class)->findAllUrlsForSiteMap(false);

        foreach ($spareParts as $sparePartUrl){
            $citiesUrls = $this->em->getRepository(City::class)->findAllUrlsForSiteMap(false);

            foreach ($citiesUrls as $cityUrl){
                $parameters = ["urlBrand" => $brandUrl, "urlModel" => $modelUrl, "urlSP" => $sparePartUrl["url"],
                    "urlCity" => $cityUrl["url"]];

                $urls[] = $this->router->generate("show_product_general_view",
                    $parameters, UrlGeneratorInterface::ABSOLUTE_URL);
            }
        }

        return $urls;
    }
}