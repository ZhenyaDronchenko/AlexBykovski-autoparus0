<?php

namespace App\SiteMap\Provider;

use App\Entity\Advert\AutoSparePart\AutoSparePartSpecificAdvert;
use App\Entity\City;
use App\Entity\SparePart;
use App\SiteMap\SiteMapUrlProvider;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class FreshProductPagesSiteMapUrlProvider extends BaseSitemapProvider implements SiteMapUrlProvider
{
    const COUNT = 500;

    public function provideIndex(): array
    {
        $allCount = (int)$this->em->getRepository(AutoSparePartSpecificAdvert::class)->getAllCount();

        $baseUrl = $this->router->generate("homepage", [], UrlGeneratorInterface::ABSOLUTE_URL);

        $urls = [];

        foreach (range(0, (int)($allCount / self::COUNT)) as $number){
            $urls[] = $baseUrl . 'sitemap_' . $number . '.xml';
        }

        return $urls;
    }

    public function provideSimple(string $type): array
    {
        //$adverts = $this->em->getRepository(AutoSparePartSpecificAdvert::class)->find([], ["createdAt" => "DESC"], self::COUNT);
        $adverts = $this->em->getRepository(AutoSparePartSpecificAdvert::class)->findFreshForSitemap((int)$type);

        $urls = [];

        foreach ($adverts as $advert){
            $city = $this->em->getRepository(City::class)->findOneBy(["name" => $advert["cityName"]]);
            $sparePart = $this->em->getRepository(SparePart::class)->findOneBy(["name" => $advert["spName"]]);

            if(!($city instanceof City) || !($sparePart instanceof SparePart)){
                continue;
            }

            $parameters = [
                "urlBrand" => $advert["urlBrand"],
                "urlModel" => $advert["urlModel"],
                "urlSP" => $sparePart->getUrl(),
                "urlCity" => $city->getUrl(),
                "id" => $advert["id"],
            ];

            $urls[] = $this->router->generate("show_product_city_view",
                $parameters, UrlGeneratorInterface::ABSOLUTE_URL);
        }

        return $urls;
    }
}