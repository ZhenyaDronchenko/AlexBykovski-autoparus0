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

    const COUNT = 10000;

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
                return [];
        }
    }

    public function provideIndex(): array
    {
        //$adverts = $this->em->getRepository(AutoSparePartSpecificAdvert::class)->find([], ["createdAt" => "DESC"], self::COUNT);
        $adverts = $this->em->getRepository(AutoSparePartSpecificAdvert::class)->findFreshForSitemap();
        $urls = [];

        foreach ($adverts as $advert){
            $parameters = [
                "urlBrand" => $advert["urlBrand"],
                "urlModel" => $advert["urlModel"],
                "urlSP" => $advert["urlSP"],
                "urlCity" => $advert["urlCity"],
                "id" => $advert["id"],
            ];

            $urls[] = $this->router->generate("show_product_city_view",
                $parameters, UrlGeneratorInterface::ABSOLUTE_URL);
        }

        return $urls;
    }

    public function provideSimple(string $type): array
    {
        // TODO: Implement provideSimple() method.
    }
}