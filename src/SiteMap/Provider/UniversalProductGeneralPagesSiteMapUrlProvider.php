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

class UniversalProductGeneralPagesSiteMapUrlProvider implements SiteMapUrlProvider
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