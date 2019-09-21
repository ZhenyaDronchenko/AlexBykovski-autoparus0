<?php

namespace App\SiteMap\Provider;

use App\Entity\Article\Article;
use App\SiteMap\SiteMapUrlProvider;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AllArticlesSiteMapUrlProvider extends BaseSitemapProvider implements SiteMapUrlProvider
{
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
}