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

class ArticleCatalogController extends Controller
{
    /**
     * @Route("/stati", name="article_catalog_choice_theme")
     */
    public function showChoiceThemePageAction(Request $request)
    {
        $themes = $this->getDoctrine()->getRepository(ArticleTheme::class)->findBy(
            [
                "isEnable" => true,
            ],
            ["orderIndex" => "ASC"]);

        return $this->render('client/catalog/article/choice-theme.html.twig', [
            "themes" => $themes
        ]);
    }

    /**
     * @Route("/stati/{urlTheme}", name="article_catalog_choice_article")
     */
    public function showChoiceArticlePageAction(Request $request, $urlTheme)
    {
        $theme = $this->getDoctrine()->getRepository(ArticleTheme::class)->findOneBy(["url" => $urlTheme]);

        $themes = $this->getDoctrine()->getRepository(ArticleTheme::class)->findBy(
            [
                "isEnable" => true,
            ],
            ["orderIndex" => "ASC"]);

        return $this->render('client/catalog/article/choice-article.html.twig', [
            "themeCurrent" => $theme ?: new ArticleTheme(),
            "themes" => $themes
        ]);
    }

    /**
     * @Route("/stati/{urlTheme}/{id}", name="old_article_catalog_show_article", options={"expose"=true})
     *
     * @ParamConverter("article", class="App\Entity\Article\Article", options={"id" = "id"})
     */
    public function showArticleOldPageAction(Request $request, $urlTheme, Article $article)
    {
        return $this->redirectToRoute("article_catalog_show_article", ["id" => $article->getId()]);
    }

    /**
     * @Route("/publication/{id}", name="article_catalog_show_article", options={"expose"=true})
     *
     * @ParamConverter("article", class="App\Entity\Article\Article", options={"id" = "id"})
     */
    public function showArticlePageAction(Request $request, Article $article)
    {
        $theme = $article->getDetail()->getThemes()->first();

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