<?php

namespace App\Controller\Catalog;

use App\Entity\Advert\AutoSparePart\AutoSparePartGeneralAdvert;
use App\Entity\Advert\AutoSparePart\AutoSparePartSpecificAdvert;
use App\Entity\Article\Article;
use App\Entity\Article\ArticleTheme;
use App\Entity\Article\ArticleType;
use App\Entity\Brand;
use App\Entity\Catalog\SparePart\CatalogSparePartChoiceBrand;
use App\Entity\Catalog\SparePart\CatalogSparePartChoiceCity;
use App\Entity\Catalog\SparePart\CatalogSparePartChoiceFinalPage;
use App\Entity\Catalog\SparePart\CatalogSparePartChoiceInStock;
use App\Entity\Catalog\SparePart\CatalogSparePartChoiceModel;
use App\Entity\Catalog\SparePart\CatalogSparePartChoiceSparePart;
use App\Entity\City;
use App\Entity\General\NotFoundPage;
use App\Entity\Model;
use App\Entity\SparePart;
use App\Provider\Advert\AdvertProvider;
use App\Provider\Advert\GeneralAdvertProvider;
use App\Provider\Advert\SpecificAdvertProvider;
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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/zapchasti")
 */
class SparePartCatalogController extends Controller
{
    /**
     * @Route("/", name="show_spare_part_catalog_choice_spare_part")
     */
    public function showCatalogChoiceSparePartAction(Request $request)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $allSpareParts = $em->getRepository(SparePart::class)->findBy(["active" => true], ["name" => "ASC"]);

        $popularSpareParts = array_filter($allSpareParts, function(SparePart $sparePart){
            return $sparePart->isPopular();
        });

        return $this->render('client/catalog/spare-part/choice-spare-part.html.twig', [
            'allSpareParts' => $allSpareParts,
            'popularSpareParts' => $popularSpareParts,
            'page' => $em->getRepository(CatalogSparePartChoiceSparePart::class)->findAll()[0],
            'articles' => $this->getUpdatedArticles(),
        ]);
    }

    /**
     * @Route("/{urlSP}", name="show_spare_part_catalog_choice_brand")
     */
    public function showCatalogChoiceBrandAction(Request $request, $urlSP, VariableTransformer $transformer)
    {
        $em = $this->getDoctrine()->getManager();
        $sparePart = $em->getRepository(SparePart::class)->findOneBy(["url" => $urlSP]);

        if(!($sparePart instanceof SparePart)){
            throw new NotFoundHttpException(NotFoundPage::DEFAULT_MESSAGE);
        }

        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $allBrands = $em->getRepository(Brand::class)->findBy(["active" => true], ["name" => "ASC"]);

        $popularBrands = array_filter($allBrands, function(Brand $brand){
            return $brand->isPopular();
        });

        $page = $em->getRepository(CatalogSparePartChoiceBrand::class)->findAll()[0];

        return $this->render('client/catalog/spare-part/choice-brand.html.twig', [
            'allBrands' => $allBrands,
            'popularBrands' => $popularBrands,
            'page' => $transformer->transformPage($page, [$sparePart]),
            'sparePart' => $sparePart,
            'articles' => $this->getUpdatedArticles(),
        ]);
    }

    /**
     * @Route("/{urlSP}/{urlBrand}", name="show_spare_part_catalog_choice_model")
     */
    public function showCatalogChoiceModelAction(Request $request, $urlSP, $urlBrand, VariableTransformer $transformer)
    {
        $em = $this->getDoctrine()->getManager();
        $sparePart = $em->getRepository(SparePart::class)->findOneBy(["url" => $urlSP]);
        $brand = $em->getRepository(Brand::class)->findOneBy(["url" => $urlBrand]);

        if(!($sparePart instanceof SparePart) || !($brand instanceof Brand)){
            throw new NotFoundHttpException(NotFoundPage::DEFAULT_MESSAGE);
        }

        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $allModels = $em->getRepository(Model::class)->findBy([
            "active" => true,
            "brand" => $brand,
        ],
            ["name" => "ASC"]);

        $popularModels = array_filter($allModels, function(Model $model){
            return $model->isPopular();
        });

        $page = $em->getRepository(CatalogSparePartChoiceModel::class)->findAll()[0];

        return $this->render('client/catalog/spare-part/choice-model.html.twig', [
            'allModels' => $allModels,
            'popularModels' => $popularModels,
            'page' => $transformer->transformPage($page, [$sparePart, $brand]),
            'brand' => $brand,
            'sparePart' => $sparePart,
            'articles' => $this->getUpdatedArticles(),
        ]);
    }

    /**
     * @Route("/{urlSP}/{urlBrand}/{urlModel}", name="show_spare_part_catalog_choice_city")
     */
    public function showCatalogChoiceCityAction(
        Request $request,
        $urlSP,
        $urlBrand,
        $urlModel,
        VariableTransformer $transformer,
        TitleProvider $titleProvider,
        AdvertProvider $advertProvider
    )
    {
        $em = $this->getDoctrine()->getManager();
        $sparePart = $em->getRepository(SparePart::class)->findOneBy(["url" => $urlSP]);
        $brand = $em->getRepository(Brand::class)->findOneBy(["url" => $urlBrand]);
        $isAllModels = $urlModel === CatalogSparePartChoiceModel::ALL_MODELS_URL;
        $model = $isAllModels ? $urlModel : $em->getRepository(Model::class)->findOneBy(["url" => $urlModel]);
        $modelObject = $model instanceof Model ? $model : null;

        if(!($sparePart instanceof SparePart) || !($brand instanceof Brand) || !($model instanceof Model) && !$isAllModels){
            throw new NotFoundHttpException(NotFoundPage::DEFAULT_MESSAGE);
        }

        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $capitals = $em->getRepository(City::class)->findBy(
            ["type" => City::CAPITAL_TYPE],
            ["name" => "ASC"]
        );

        $regionalCities = $em->getRepository(City::class)->findBy(
            ["type" => City::REGIONAL_CITY_TYPE],
            ["name" => "ASC"]
        );

        $page = $em->getRepository(CatalogSparePartChoiceCity::class)->findAll()[0];
        $transformParameters = $model instanceof Model ? [$sparePart, $brand, $model] : [$sparePart, $brand];

        $parsedCapitals = [];
        $parsedRegionalCities = [];

        foreach ($capitals as $city){
            $parsedCapitals[] = [
                "object" => $city,
                "title" => $titleProvider->getSinglePageTitle(CatalogSparePartChoiceInStock::class,
                    array_merge($transformParameters, [$city])),
            ];
        }

        foreach ($regionalCities as $city){
            $parsedRegionalCities[] = [
                "object" => $city,
                "title" => $titleProvider->getSinglePageTitle(CatalogSparePartChoiceInStock::class,
                    array_merge($transformParameters, [$city])),
            ];
        }

        $catalogFilter = new CatalogAdvertFilterType($brand, $modelObject, $sparePart, null, null);

        return $this->render('client/catalog/spare-part/choice-city.html.twig', [
            'capitals' => $parsedCapitals,
            'regionalCities' => $parsedRegionalCities,
            'brand' => $brand,
            'sparePart' => $sparePart,
            'model' => $modelObject,
            'specificAdverts' => $advertProvider->provideSortedSpecificAdverts($catalogFilter),
            'generalAdverts' => $advertProvider->provideSortedGeneralAdverts($catalogFilter),
            'page' => $transformer->transformPage($page, $transformParameters),
            'allCitiesTitle' => $titleProvider->getSinglePageTitle(CatalogSparePartChoiceInStock::class, $transformParameters),
            'articles' => $this->getUpdatedArticles(),
        ]);
    }

    /**
     * @Route("/{urlSP}/{urlBrand}/{urlModel}/{urlCity}", name="show_spare_part_catalog_in_stock")
     */
    public function showCatalogInStockAction(
        Request $request,
        $urlSP,
        $urlBrand,
        $urlModel,
        $urlCity,
        VariableTransformer $transformer,
        BamperSuggestionProvider $suggestionProvider,
        TitleProvider $titleProvider,
        AdvertProvider $advertProvider
    )
    {
        $em = $this->getDoctrine()->getManager();
        $sparePart = $em->getRepository(SparePart::class)->findOneBy(["url" => $urlSP]);
        $brand = $em->getRepository(Brand::class)->findOneBy(["url" => $urlBrand]);
        $isAllModels = $urlModel === CatalogSparePartChoiceModel::ALL_MODELS_URL;
        $isAllCities = $urlCity === City::ALL_CITIES;
        $model = $isAllModels ? $urlModel : $em->getRepository(Model::class)->findOneBy(["url" => $urlModel]);
        $city = $isAllCities ? $urlModel : $em->getRepository(City::class)->findOneBy(["url" => $urlCity]);
        $modelObject = $model instanceof Model ? $model : null;
        $cityObject = $city instanceof City ? $city : null;

        if(!($sparePart instanceof SparePart) || !($brand instanceof Brand) ||
            !($model instanceof Model) && !$isAllModels || !($city instanceof City) && !$isAllCities){
            throw new NotFoundHttpException(NotFoundPage::DEFAULT_MESSAGE);
        }

        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $page = $em->getRepository(CatalogSparePartChoiceInStock::class)->findAll()[0];

        $transformParameters = [$sparePart, $brand];

        if($model instanceof Model){
            $transformParameters[] = $model;
        }

        if($city instanceof City){
            $transformParameters[] = $city;
        }

        $catalogFilter = new CatalogAdvertFilterType($brand, $modelObject, $sparePart, $cityObject, null);
        $specificAdverts = $advertProvider->provideSortedSpecificAdverts($catalogFilter);
        $generalAdverts = $advertProvider->provideSortedGeneralAdverts($catalogFilter);
        $bamberSuggestions = (count($specificAdverts) + count($generalAdverts)) ? [] : $suggestionProvider->provide($brand, $modelObject, $sparePart, $cityObject, false);

        $pageTransformed = $transformer->transformPage($page, $transformParameters);
        $pageTransformed->setReturnButtonLink($transformer->transformPage($page->getReturnButtonLink(), $transformParameters));
        $pageTransformed->setReturnButtonText($transformer->transformPage($page->getReturnButtonText(), $transformParameters));

        return $this->render('client/catalog/spare-part/in-stock.html.twig', [
            'page' => $pageTransformed,
            'sparePart' => $sparePart,
            'brand' => $brand,
            'model' => $modelObject,
            'city' => $cityObject,
            'specificAdverts' => $specificAdverts,
            'generalAdverts' => $generalAdverts,
            'bamberSuggestions' => $bamberSuggestions,
            'choiceInStockTitle' => $titleProvider->getSinglePageTitle(CatalogSparePartChoiceFinalPage::class, $transformParameters),
            'articles' => $this->getUpdatedArticles(),
        ]);
    }

    /**
     * @Route("/{urlSP}/{urlBrand}/{urlModel}/{urlCity}/in_stock", name="show_spare_part_catalog_final_page")
     */
    public function showCatalogFinalPageAction(
        Request $request,
        $urlSP,
        $urlBrand,
        $urlModel,
        $urlCity,
        VariableTransformer $transformer,
        BamperSuggestionProvider $suggestionProvider,
        AdvertProvider $advertProvider
    )
    {
        $em = $this->getDoctrine()->getManager();
        $sparePart = $em->getRepository(SparePart::class)->findOneBy(["url" => $urlSP]);
        $brand = $em->getRepository(Brand::class)->findOneBy(["url" => $urlBrand]);
        $isAllModels = $urlModel === CatalogSparePartChoiceModel::ALL_MODELS_URL;
        $isAllCities = $urlCity === City::ALL_CITIES;
        $model = $isAllModels ? $urlModel : $em->getRepository(Model::class)->findOneBy(["url" => $urlModel]);
        $city = $isAllCities ? $urlModel : $em->getRepository(City::class)->findOneBy(["url" => $urlCity]);
        $modelObject = $model instanceof Model ? $model : null;
        $cityObject = $city instanceof City ? $city : null;

        if(!($sparePart instanceof SparePart) || !($brand instanceof Brand) ||
            !($model instanceof Model) && !$isAllModels || !($city instanceof City) && !$isAllCities){
            throw new NotFoundHttpException(NotFoundPage::DEFAULT_MESSAGE);
        }

        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $page = $em->getRepository(CatalogSparePartChoiceFinalPage::class)->findAll()[0];

        $transformParameters = [$sparePart, $brand];

        if($model instanceof Model){
            $transformParameters[] = $model;
        }

        if($city instanceof City){
            $transformParameters[] = $city;
        }

        $catalogFilter = new CatalogAdvertFilterType($brand, $modelObject, $sparePart, $cityObject, true);
        $specificAdverts = $advertProvider->provideSortedSpecificAdverts($catalogFilter);
        $generalAdverts = $advertProvider->provideSortedGeneralAdverts($catalogFilter);
        $bamberSuggestions = (count($specificAdverts) + count($generalAdverts)) ? [] : $suggestionProvider->provide($brand, $modelObject, $sparePart, $cityObject);

        $pageTransformed = $transformer->transformPage($page, $transformParameters);
        $pageTransformed->setReturnButtonLink($transformer->transformPage($page->getReturnButtonLink(), $transformParameters));
        $pageTransformed->setReturnButtonText($transformer->transformPage($page->getReturnButtonText(), $transformParameters));

        return $this->render('client/catalog/spare-part/final-page.html.twig', [
            'page' => $pageTransformed,
            'sparePart' => $sparePart,
            'brand' => $brand,
            'model' => $modelObject,
            'city' => $cityObject,
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