<?php

namespace App\Controller\Catalog;

use App\Entity\Admin;
use App\Entity\Advert\AutoSparePart\AutoSparePartGeneralAdvert;
use App\Entity\Brand;
use App\Entity\Catalog\Brand\CatalogBrandChoiceBrand;
use App\Entity\Catalog\Brand\CatalogBrandChoiceCity;
use App\Entity\Catalog\Brand\CatalogBrandChoiceFinalPage;
use App\Entity\Catalog\Brand\CatalogBrandChoiceInStock;
use App\Entity\Catalog\Brand\CatalogBrandChoiceModel;
use App\Entity\Catalog\Brand\CatalogBrandChoiceSparePart;
use App\Entity\Catalog\Turbo\CatalogTurboChoiceBrand;
use App\Entity\Catalog\Turbo\CatalogTurboChoiceCity;
use App\Entity\Catalog\Turbo\CatalogTurboChoiceFinalPage;
use App\Entity\Catalog\Turbo\CatalogTurboChoiceModel;
use App\Entity\Catalog\Turbo\CatalogTurboChoiceSparePart;
use App\Entity\City;
use App\Entity\General\MainPage;
use App\Entity\General\NotFoundPage;
use App\Entity\Model;
use App\Entity\SparePart;
use App\Provider\TitleProvider;
use App\Transformer\VariableTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

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
        return $this->render('client/catalog/article/choice-theme.html.twig', [
        ]);
    }

    /**
     * @Route("/{theme}", name="article_catalog_choice_article")
     */
    public function showChoiceArticlePageAction(Request $request, $theme)
    {
        return $this->render('client/catalog/article/choice-article.html.twig', [
        ]);
    }

    /**
     * @Route("/{theme}/{urlArticle}", name="article_catalog_show_article")
     */
    public function showArticlePageAction(Request $request, $theme, $urlArticle)
    {
        return $this->render('client/catalog/article/show-article.html.twig', [
        ]);
    }
}