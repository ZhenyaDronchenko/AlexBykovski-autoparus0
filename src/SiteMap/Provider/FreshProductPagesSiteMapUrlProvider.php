<?php

namespace App\SiteMap\Provider;

use App\Entity\Advert\AutoSparePart\AutoSparePartSpecificAdvert;
use App\Entity\City;
use App\Entity\SparePart;
use App\SiteMap\SiteMapUrlProvider;
use App\SiteMap\SiteMapFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class FreshProductPagesSiteMapUrlProvider implements SiteMapUrlProvider
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var RouterInterface */
    private $router;

    /** @var string $publicPath */
    private $publicPath;

    const COUNT = 500;

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