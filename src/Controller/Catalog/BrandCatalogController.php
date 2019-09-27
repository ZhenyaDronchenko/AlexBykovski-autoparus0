<?php

namespace App\Controller\Catalog;

use App\Entity\Admin;
use App\Entity\Advert\AutoSparePart\AutoSparePartGeneralAdvert;
use App\Entity\Advert\AutoSparePart\AutoSparePartSpecificAdvert;
use App\Entity\Article\Article;
use App\Entity\Article\ArticleTheme;
use App\Entity\Article\ArticleType;
use App\Entity\Brand;
use App\Entity\Catalog\Brand\CatalogBrandChoiceBrand;
use App\Entity\Catalog\Brand\CatalogBrandChoiceCity;
use App\Entity\Catalog\Brand\CatalogBrandChoiceFinalPage;
use App\Entity\Catalog\Brand\CatalogBrandChoiceInStock;
use App\Entity\Catalog\Brand\CatalogBrandChoiceModel;
use App\Entity\Catalog\Brand\CatalogBrandChoiceSparePart;
use App\Entity\City;
use App\Entity\General\MainPage;
use App\Entity\General\NotFoundPage;
use App\Entity\Model;
use App\Entity\SparePart;
use App\Provider\Integration\BamperSuggestionProvider;
use App\Provider\TitleProvider;
use App\Transformer\VariableTransformer;
use App\Type\ArticleFilterType;
use App\Type\CatalogAdvertFilterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/brand-catalog")
 */
class BrandCatalogController extends Controller
{
    /**
     * @Route("", name="show_brand_catalog_choice_brand", options={"expose"=true})
     */
    public function showChoiceBrandPageAction(Request $request)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $allBrands = $em->getRepository(Brand::class)->findBy(["active" => true], ["name" => "ASC"]);

        $popularBrands = array_filter($allBrands, function(Brand $brand){
            return $brand->isPopular();
        });

        return $this->render('client/catalog/brand/choice-brand.html.twig', [
            'allBrands' => $allBrands,
            'popularBrands' => $popularBrands,
            'page' => $em->getRepository(CatalogBrandChoiceBrand::class)->findAll()[0],
            'articles' => $this->getUpdatedArticles(),
        ]);
    }

    /**
     * @Route("/{urlBrand}", name="show_brand_catalog_choice_model")
     */
    public function showChoiceModelPageAction(
        Request $request,
        VariableTransformer $transformer,
        $urlBrand)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $brand = $em->getRepository(Brand::class)->findOneBy(["url" => $urlBrand]);

        if(!($brand instanceof Brand)){
            throw new NotFoundHttpException(NotFoundPage::DEFAULT_MESSAGE);
        }

        $allModels = $em->getRepository(Model::class)->findBy([
            "active" => true,
            "brand" => $brand,
        ],
            ["name" => "ASC"]);

        $popularModels = array_filter($allModels, function(Model $model){
            return $model->isPopular();
        });

        $page = $em->getRepository(CatalogBrandChoiceModel::class)->findAll()[0];
        $transformParameters = [$brand];

        return $this->render('client/catalog/brand/choice-model.html.twig', [
            'allModels' => $allModels,
            'popularModels' => $popularModels,
            'page' => $transformer->transformPage($page, $transformParameters),
            'brand' => $brand,
            'parameters' => $transformParameters,
            'articles' => $this->getUpdatedArticles(),
        ]);
    }

    /**
     * @Route("/{urlBrand}/{urlModel}", name="show_brand_catalog_choice_spare_part")
     */
    public function showChoiceSparePartPageAction(
        Request $request,
        VariableTransformer $transformer,
        $urlBrand,
        $urlModel
    )
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $brand = $em->getRepository(Brand::class)->findOneBy(["url" => $urlBrand]);
        $model = $em->getRepository(Model::class)->findOneBy(["url" => $urlModel]);

        if(!($brand instanceof Brand) || !($model instanceof Model)){
            throw new NotFoundHttpException(NotFoundPage::DEFAULT_MESSAGE);
        }

        $popularSpareParts = $em->getRepository(SparePart::class)->findBy(
            [
                "active" => true,
                "popular" => true
            ],
            ["name" => "ASC"]
        );

        $allSpareParts = $em->getRepository(SparePart::class)->findBy([], ["name" => "ASC"]);

        $page = $em->getRepository(CatalogBrandChoiceSparePart::class)->findAll()[0];

        $transformParameters = [$brand, $model];

        return $this->render('client/catalog/brand/choice-spare-part.html.twig', [
            'popularSpareParts' => $popularSpareParts,
            'allSpareParts' => $allSpareParts,
            'page' => $transformer->transformPage($page, $transformParameters),
            'brand' => $brand,
            'model' => $model,
            'parameters' => $transformParameters,
            'articles' => $this->getUpdatedArticles(),
        ]);
    }

    /**
     * @Route("/{urlBrand}/{urlModel}/{urlSP}", name="show_brand_catalog_choice_city")
     */
    public function showChoiceCityPageAction(
        Request $request,
        VariableTransformer $transformer,
        BamperSuggestionProvider $suggestionProvider,
        $urlBrand,
        $urlModel,
        $urlSP
    )
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $sparePart = $em->getRepository(SparePart::class)->findOneBy(["url" => $urlSP]);
        $brand = $em->getRepository(Brand::class)->findOneBy(["url" => $urlBrand]);
        $model = $em->getRepository(Model::class)->findOneBy(["url" => $urlModel]);

        if(!($sparePart instanceof SparePart) || !($brand instanceof Brand) || !($model instanceof Model)){
            throw new NotFoundHttpException(NotFoundPage::DEFAULT_MESSAGE);
        }

        $capital = $em->getRepository(City::class)->findOneBy(
            ["type" => City::CAPITAL_TYPE],
            ["name" => "ASC"]
        );

        $regionalCities = $em->getRepository(City::class)->findBy(
            ["type" => City::REGIONAL_CITY_TYPE],
            ["name" => "ASC"]
        );

        $othersCities = $em->getRepository(City::class)->findBy(
            [
                "type" => City::OTHERS_TYPE,
                "active" => true,
            ],
            ["name" => "ASC"]
        );

        $page = $em->getRepository(CatalogBrandChoiceCity::class)->findAll()[0];

        $transformParameters = [$sparePart, $brand, $model];
        $catalogFilter = new CatalogAdvertFilterType($brand, $model, $sparePart, null, null);

        $specificAdverts = $em->getRepository(AutoSparePartSpecificAdvert::class)->findAllForCatalog($catalogFilter);
        $generalAdverts = $em->getRepository(AutoSparePartGeneralAdvert::class)->findAllForCatalog($catalogFilter);
        $bamberSuggestions = (count($specificAdverts) + count($generalAdverts)) ? [] : $suggestionProvider->provide($brand, $model, $sparePart, null, false);

        return $this->render('client/catalog/brand/choice-city.html.twig', [
            'capitals' => [$capital],
            'regionalCities' => array_merge([$capital], $regionalCities),
            'otherCities' => $othersCities,
            'page' => $transformer->transformPage($page, $transformParameters),
            'sparePart' => $sparePart,
            'brand' => $brand,
            'model' => $model,
            'specificAdverts' => $specificAdverts,
            'generalAdverts' => $generalAdverts,
            'bamberSuggestions' => $bamberSuggestions,
            'parameters' => $transformParameters,
            'articles' => $this->getUpdatedArticles(),
        ]);
    }

    /**
     * @Route("/{urlBrand}/{urlModel}/{urlSP}/{urlCity}", name="show_brand_catalog_in_stock", options={"expose"=true})
     */
    public function showCatalogInStockAction(
        Request $request,
        VariableTransformer $transformer,
        BamperSuggestionProvider $suggestionProvider,
        $urlSP,
        $urlBrand,
        $urlModel,
        $urlCity
    )
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $sparePart = $em->getRepository(SparePart::class)->findOneBy(["url" => $urlSP]);
        $brand = $em->getRepository(Brand::class)->findOneBy(["url" => $urlBrand]);
        $isAllCities = $urlCity === City::ALL_CITIES;
        $model = $em->getRepository(Model::class)->findOneBy(["url" => $urlModel]);
        $city = $isAllCities ? $urlCity : $em->getRepository(City::class)->findOneBy(["url" => $urlCity]);

        if(!($sparePart instanceof SparePart) || !($brand instanceof Brand) ||
            !($model instanceof Model) || !($city instanceof City) && !$isAllCities){
            throw new NotFoundHttpException(NotFoundPage::DEFAULT_MESSAGE);
        }

        $page = $em->getRepository(CatalogBrandChoiceInStock::class)->findAll()[0];
        $transformParameters = $city instanceof City ? [$sparePart, $brand, $model, $city] : [$sparePart, $brand, $model];
        $cityParameter = $city instanceof City ? $city : null;
        $catalogFilter = new CatalogAdvertFilterType($brand, $model, $sparePart, $cityParameter, null);

        $specificAdverts = $em->getRepository(AutoSparePartSpecificAdvert::class)->findAllForCatalog($catalogFilter);
        $generalAdverts = $em->getRepository(AutoSparePartGeneralAdvert::class)->findAllForCatalog($catalogFilter);
        $bamberSuggestions = (count($specificAdverts) + count($generalAdverts)) ? [] : $suggestionProvider->provide($brand, $model, $sparePart, $cityParameter, false);

        return $this->render('client/catalog/brand/only-in-stock.html.twig', [
            'page' => $transformer->transformPage($page, $transformParameters),
            'sparePart' => $sparePart,
            'brand' => $brand,
            'model' => $model,
            'city' => $isAllCities ? null : $city,
            'parameters' => $transformParameters,
            'specificAdverts' => $specificAdverts,
            'generalAdverts' => $generalAdverts,
            'bamberSuggestions' => $bamberSuggestions,
            'articles' => $this->getUpdatedArticles(),
        ]);
    }

    /**
     * @Route("/{urlBrand}/{urlModel}/{urlSP}/{urlCity}/in_stock", name="show_brand_catalog_final_page")
     */
    public function showCatalogFinalPageAction(
        Request $request,
        VariableTransformer $transformer,
        BamperSuggestionProvider $suggestionProvider,
        $urlSP,
        $urlBrand,
        $urlModel,
        $urlCity
    )
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $sparePart = $em->getRepository(SparePart::class)->findOneBy(["url" => $urlSP]);
        $brand = $em->getRepository(Brand::class)->findOneBy(["url" => $urlBrand]);
        $isAllCities = $urlCity === City::ALL_CITIES;
        $model = $em->getRepository(Model::class)->findOneBy(["url" => $urlModel]);
        $city = $isAllCities ? $urlCity : $em->getRepository(City::class)->findOneBy(["url" => $urlCity]);

        if(!($sparePart instanceof SparePart) || !($brand instanceof Brand) ||
            !($model instanceof Model) || !($city instanceof City) && !$isAllCities){
            throw new NotFoundHttpException(NotFoundPage::DEFAULT_MESSAGE);
        }

        $page = $em->getRepository(CatalogBrandChoiceFinalPage::class)->findAll()[0];
        $transformParameters = $city instanceof City ? [$sparePart, $brand, $model, $city] : [$sparePart, $brand, $model];

        $cityParameter = $city instanceof City ? $city : null;
        $catalogFilter = new CatalogAdvertFilterType($brand, $model, $sparePart, $cityParameter, true);

        $specificAdverts = $em->getRepository(AutoSparePartSpecificAdvert::class)->findAllForCatalog($catalogFilter);
        $generalAdverts = $em->getRepository(AutoSparePartGeneralAdvert::class)->findAllForCatalog($catalogFilter);
        $bamberSuggestions = (count($specificAdverts) + count($generalAdverts)) ? [] : $suggestionProvider->provide($brand, $model, $sparePart, $cityParameter, true);

        return $this->render('client/catalog/brand/final-page.html.twig', [
            'page' => $transformer->transformPage($page, $transformParameters),
            'sparePart' => $sparePart,
            'brand' => $brand,
            'model' => $model,
            'city' => $isAllCities ? null : $city,
            'parameters' => $transformParameters,
            'specificAdverts' => $specificAdverts,
            'generalAdverts' => $generalAdverts,
            'bamberSuggestions' => $bamberSuggestions,
            'articles' => $this->getUpdatedArticles(),
        ]);
    }

    private function getUpdatedArticles()
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $newsTheme = $em->getRepository(ArticleTheme::class)->findOneBy(["url" => ArticleTheme::NEWS_THEME]);
        $ourType = $em->getRepository(ArticleType::class)->findOneBy(["type" => ArticleType::OUR_UNIQUE_MATERIAL]);

        return $em->getRepository(Article::class)
            ->findAllByFilter(new ArticleFilterType(ArticleFilterType::SORT_CREATED, [$newsTheme], 7, 0, [], [], [$ourType]));
    }
}