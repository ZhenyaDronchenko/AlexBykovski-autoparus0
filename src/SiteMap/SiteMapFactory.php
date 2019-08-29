<?php

namespace App\SiteMap;

use App\Entity\SEO\SiteMap;
use App\SiteMap\Provider\BrandCatalogPopularOBD2TurboCitySiteMapUrlProvider;
use App\SiteMap\Provider\BrandCatalogPopularSiteMapUrlProvider;
use App\SiteMap\Provider\BrandCatalogSiteMapUrlProvider;
use App\SiteMap\Provider\FreshProductPagesSiteMapUrlProvider;
use App\SiteMap\Provider\SparePartCatalogOBD2AllCititesSiteMapUrlProvider;
use App\SiteMap\Provider\SparePartCatalogOBD2MinskSiteMapUrlProvider;
use App\SiteMap\Provider\ForumObd2ErrorsObd2UniversalPagesSiteMapUrlProvider;
use App\SiteMap\Provider\UniversalProductGeneralPagesSiteMapUrlProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;

final class SiteMapFactory
{
    const SITE_MAP_NAME = "sitemap";
    const SITE_MAP_INDEX = "index";

    /** @var EntityManagerInterface */
    private $em;

    /** @var RouterInterface */
    private $router;

    /** @var string */
    private $publicPath;

    /**
     * SiteMapFactory constructor.
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

    /**
     * @param string $type
     *
     * @return SiteMapUrlProvider
     */
    public function factory(string $type): SiteMapUrlProvider
    {
        switch($type){
            case SiteMap::TYPE_BRAND_CATALOG:
                return new BrandCatalogSiteMapUrlProvider($this->em, $this->router, $this->publicPath);
            case SiteMap::TYPE_BRAND_CATALOG_POPULAR:
                return new BrandCatalogPopularSiteMapUrlProvider($this->em, $this->router, $this->publicPath);
            case SiteMap::TYPE_SPARE_PART_CATALOG_OBD2_MINSK:
                return new SparePartCatalogOBD2MinskSiteMapUrlProvider($this->em, $this->router, $this->publicPath);
            case SiteMap::TYPE_SPARE_PART_CATALOG_OBD2_ALL_CITIES:
                return new SparePartCatalogOBD2AllCititesSiteMapUrlProvider($this->em, $this->router, $this->publicPath);
            case SiteMap::TYPE_BRAND_CATALOG_OBD2_TURBO_CITY:
                return new BrandCatalogPopularOBD2TurboCitySiteMapUrlProvider($this->em, $this->router, $this->publicPath);
            case SiteMap::TYPE_FORUM_OBD2_ERRORS_OBD2_UNIVERSAL_PAGES:
                return new ForumObd2ErrorsObd2UniversalPagesSiteMapUrlProvider($this->em, $this->router, $this->publicPath);
            case SiteMap::TYPE_UNIVERSAL_PRODUCT_GENERAL_PAGES:
                return new UniversalProductGeneralPagesSiteMapUrlProvider($this->em, $this->router, $this->publicPath);
            case SiteMap::TYPE_FRESH_PRODUCT_PAGES:
                return new FreshProductPagesSiteMapUrlProvider($this->em, $this->router, $this->publicPath);
            default:
                throw new \InvalidArgumentException('Unknown builder given');
        }
    }
}