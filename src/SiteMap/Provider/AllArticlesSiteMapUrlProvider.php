<?php

namespace App\SiteMap\Provider;

use App\Entity\Advert\AutoSparePart\AutoSparePartSpecificAdvert;
use App\Entity\Article\Article;
use App\Entity\City;
use App\Entity\SparePart;
use App\SiteMap\SiteMapUrlProvider;
use App\SiteMap\SiteMapFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class AllArticlesSiteMapUrlProvider implements SiteMapUrlProvider
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
                return [];
        }
    }

    public function provideIndex(): array
    {
        $articles = $this->em->getRepository(Article::class)->findAllOnlyId(true);

        $urls = [];

        foreach ($articles as $article){
            $urls[] = $this->router->generate("article_catalog_show_article",
                ["id" => $article["id"]], UrlGeneratorInterface::ABSOLUTE_URL);;
        }

        return $urls;
    }

    public function provideSimple(string $type): array
    {
        return [];
    }
}