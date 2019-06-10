<?php

namespace App\Controller\Catalog;

use App\Entity\Article\Article;
use App\Entity\Article\ArticleTheme;
use App\Entity\Brand;
use App\Entity\SparePart;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/stati")
 */
class ArticleCatalogController extends Controller
{
    /**
     * @Route("", name="article_catalog_choice_theme")
     */
    public function showChoiceThemePageAction(Request $request)
    {
        $themes = $this->getDoctrine()->getRepository(ArticleTheme::class)->findBy([], ["theme" => "DESC"]);

        return $this->render('client/catalog/article/choice-theme.html.twig', [
            "themes" => $themes
        ]);
    }

    /**
     * @Route("/{urlTheme}", name="article_catalog_choice_article")
     */
    public function showChoiceArticlePageAction(Request $request, $urlTheme)
    {
        $theme = $this->getDoctrine()->getRepository(ArticleTheme::class)->findOneBy(["url" => $urlTheme]);

        return $this->render('client/catalog/article/choice-article.html.twig', [
            "theme" => $theme ?: new ArticleTheme()
        ]);
    }

    /**
     * @Route("/{urlTheme}/{id}", name="article_catalog_show_article", options={"expose"=true})
     *
     * @ParamConverter("article", class="App\Entity\Article\Article", options={"id" = "id"})
     */
    public function showArticlePageAction(Request $request, $urlTheme, Article $article)
    {
        $theme = $this->getDoctrine()->getRepository(ArticleTheme::class)->findOneBy(["url" => $urlTheme]);

        $brands = $this->getDoctrine()->getRepository(Brand::class)->findBy(["active" => true], ["name" => "ASC"]);
        $spareParts = $this->getDoctrine()->getRepository(SparePart::class)->findBy(["active" => true], ["name" => "ASC"]);

        $parsedBrands = [];
        $parsedSpareParts = [];

        /** @var Brand $brand */
        foreach ($brands as $brand){
            $parsedBrands[] = [
                "label" => $brand->getName(),
                "url" => $brand->getUrl(),
            ];
        }

        /** @var SparePart $sparePart */
        foreach ($spareParts as $sparePart){
            $parsedSpareParts[] = [
                "label" => $sparePart->getName(),
                "url" => $sparePart->getUrl(),
            ];
        }

        return $this->render('client/catalog/article/show-article.html.twig', [
            "article" => $article,
            "theme" => $theme ?: new ArticleTheme(),
            "brands" => $parsedBrands,
            "spareParts" => $parsedSpareParts,
        ]);
    }
}