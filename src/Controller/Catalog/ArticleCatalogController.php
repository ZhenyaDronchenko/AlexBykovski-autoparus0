<?php

namespace App\Controller\Catalog;

use App\Entity\Article\Article;
use App\Entity\Article\ArticleTheme;
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
        return $this->render('client/catalog/article/choice-article.html.twig', [
        ]);
    }

    /**
     * @Route("/{urlTheme}/{id}", name="article_catalog_show_article", options={"expose"=true})
     *
     * @ParamConverter("article", class="App:Article", options={"id" = "id"})
     */
    public function showArticlePageAction(Request $request, $urlTheme, Article $article)
    {
        return $this->render('client/catalog/article/show-article.html.twig', [
        ]);
    }
}