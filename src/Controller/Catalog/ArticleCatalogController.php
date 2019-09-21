<?php

namespace App\Controller\Catalog;

use App\Entity\Article\Article;
use App\Entity\Article\ArticleComment;
use App\Entity\Article\ArticleTheme;
use App\Entity\Brand;
use App\Entity\SparePart;
use App\Helper\ArticleCommentHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

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

        return $this->render('client/catalog/article/choice-article.html.twig', [
            "themeCurrent" => $theme ?: new ArticleTheme(),
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

    /**
     * @Route("/ajax/get-comments/{id}", name="article_catalog_get_comments", options={"expose"=true})
     *
     * @ParamConverter("article", class="App\Entity\Article\Article", options={"id" = "id"})
     */
    public function getAllCommentsAction(Request $request, Article $article, ArticleCommentHelper $helper)
    {
        $comments = $this->getDoctrine()->getRepository(ArticleComment::class)->findBy([
                "article" => $article,
                "parent" => null,
            ]);

        return new JsonResponse($helper->parseComments($comments));
    }

    /**
     * @Route("/ajax/add-comments/{id}/", name="article_catalog_send_comment", options={"expose"=true})
     *
     * @ParamConverter("article", class="App\Entity\Article\Article", options={"id" = "id"})
     *
     * @Security("has_role('ROLE_CLIENT')")
     */
    public function sendCommentAction(
        Request $request,
        Article $article,
        ArticleCommentHelper $helper
    )
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent(), true);
        $text = $data["text"];
        $parentId = $data["parent"];

        $parent = $parentId ? $em->getRepository(ArticleComment::class)->find($parentId) : null;

        if(!$text || $parent instanceof ArticleComment && $parent->getArticle()->getId() !== $article->getId()){
            return new JsonResponse([
                "success" => false,
                "message" => "Некорретные данные",
                "text" => $text,
            ]);
        }

        $comment = new ArticleComment($text, $article, $this->getUser(), $parent);

        $em->persist($comment);
        $em->flush();

        return new JsonResponse([
            "success" => true,
            "message" => $comment->toArray(),
        ]);
    }
}