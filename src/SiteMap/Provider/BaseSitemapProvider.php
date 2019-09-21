<?php

namespace App\SiteMap\Provider;

use App\Entity\Article\Article;
use App\SiteMap\SiteMapFactory;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class BaseSitemapProvider
{
    /** @var EntityManagerInterface */
    protected $em;

    /** @var RouterInterface */
    protected $router;

    /** @var string $publicPath */
    protected $publicPath;

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
                //return array_merge([$this->getNewsSiteMapIndexUrl()], $this->provideIndex());
                return $this->provideIndex();
            case SiteMapFactory::SITE_MAP_NEWS:
                //return $this->getNewsPublicationUrls();
                return [];
            default:
                return $this->provideSimple($type);
        }
    }

    public function provideIndex()
    {
        return [];
    }

    public function provideSimple(string $type): array
    {
        return [];
    }

    public function getNewsSiteMapIndexUrl()
    {
        return $this->router->generate("homepage", [], UrlGeneratorInterface::ABSOLUTE_URL) . 'sitemap_news.xml';
    }

    public function getNewsPublicationUrls()
    {
        $articles = $this->em->getRepository(Article::class)->findAllForSitemap();

        $parsedArticles = [];

        /** @var Article $article */
        foreach ($articles as $article){
            $parsedArticles[] = [
                "url" => $this->router->generate("article_catalog_show_article", ["id" => $article->getId()],
                    UrlGeneratorInterface::ABSOLUTE_URL),
                "date" => $article->getCreatedAt()->format("Y-m-d"),
                "title" => $article->getHeadline1(),
            ];
        }

        return $parsedArticles;
    }
}