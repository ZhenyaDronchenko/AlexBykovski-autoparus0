<?php

namespace App\SiteMap;

use App\Entity\SiteMap;
use App\SiteMap\Provider\BrandCatalogPopularSiteMapUrlProvider;
use App\SiteMap\Provider\BrandCatalogSiteMapUrlProvider;
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
            default:
                throw new \InvalidArgumentException('Unknown builder given');
        }
    }
}