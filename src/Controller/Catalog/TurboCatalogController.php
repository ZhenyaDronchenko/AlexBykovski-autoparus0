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
use App\Entity\Model;
use App\Entity\SparePart;
use App\Provider\TitleProvider;
use App\Transformer\VariableTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/turbo-catalog")
 */
class TurboCatalogController extends Controller
{
    /**
     * @Route("", name="turbo_catalog_choice_brand")
     */
    public function showChoiceBrandPageAction(Request $request, TitleProvider $titleProvider)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $brands = $em->getRepository(Brand::class)->findBy([
            "active" => true,
            "popular" => true,
        ], ["name" => "ASC"]);

        return $this->render('client/catalog/turbo/choice-brand.html.twig', [
            'brands' => $brands,
            'page' => $em->getRepository(CatalogTurboChoiceBrand::class)->findAll()[0],
            'homepageTitle' => $titleProvider->getSinglePageTitle(MainPage::class),
        ]);
    }

    /**
     * @Route("/{urlBrand}", name="turbo_catalog_choice_model")
     */
    public function showChoiceModelPageAction(
        Request $request,
        $urlBrand,
        VariableTransformer $transformer,
        TitleProvider $titleProvider)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $brand = $em->getRepository(Brand::class)->findOneBy(["url" => $urlBrand]);

        if(!($brand instanceof Brand)){
            return $this->redirect($request->headers->get('referer'));
        }

        $allModels = $em->getRepository(Model::class)->findBy([
            "active" => true,
            "brand" => $brand,
        ],
            ["name" => "ASC"]);

        $popularModels = array_filter($allModels, function(Model $model){
            return $model->isPopular();
        });

        $page = $em->getRepository(CatalogTurboChoiceModel::class)->findAll()[0];
        $transformParameters = [$brand];

        return $this->render('client/catalog/turbo/choice-model.html.twig', [
            'allModels' => $allModels,
            'popularModels' => $popularModels,
            'page' => $transformer->transformPage($page, $transformParameters),
            'brand' => $brand,
            'brandToyota' => $em->getRepository(Brand::class)->findOneBy(["url" => Brand::TOYOTA_RUS_URL]),
            'homepageTitle' => $titleProvider->getSinglePageTitle(MainPage::class),
            'choiceBrandTitle' => $titleProvider->getSinglePageTitle(CatalogTurboChoiceBrand::class, $transformParameters),
        ]);
    }

    /**
     * @Route("/{urlBrand}/{urlModel}", name="turbo_catalog_choice_spare_part")
     */
    public function showChoiceSparePartPageAction(
        Request $request,
        $urlBrand,
        $urlModel,
        VariableTransformer $transformer,
        TitleProvider $titleProvider
    )
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $brand = $em->getRepository(Brand::class)->findOneBy(["url" => $urlBrand]);
        $model = $em->getRepository(Model::class)->findOneBy(["url" => $urlModel]);

        if(!($brand instanceof Brand) || !($model instanceof Model)){
            return $this->redirect($request->headers->get('referer'));
        }

        $popularSpareParts = $em->getRepository(SparePart::class)->findBy(
            [
                "active" => true,
                "popular" => true
            ],
            ["name" => "ASC"]
        );

        $unpopularSpareParts = $em->getRepository(SparePart::class)->findBy(
            [
                "active" => true,
                "popular" => false
            ],
            ["name" => "ASC"]
        );

        $page = $em->getRepository(CatalogTurboChoiceSparePart::class)->findAll()[0];
        $transformParameters = [$brand, $model];

        return $this->render('client/catalog/turbo/choice-spare-part.html.twig', [
            'popularSpareParts' => $popularSpareParts,
            'unpopularSpareParts' => $unpopularSpareParts,
            'page' => $transformer->transformPage($page, $transformParameters),
            'brand' => $brand,
            'model' => $model,
            'homepageTitle' => $titleProvider->getSinglePageTitle(MainPage::class),
            'choiceBrandTitle' => $titleProvider->getSinglePageTitle(CatalogTurboChoiceBrand::class, $transformParameters),
            'choiceModelTitle' => $titleProvider->getSinglePageTitle(CatalogTurboChoiceModel::class, $transformParameters),
        ]);
    }

    /**
     * @Route("/{urlBrand}/{urlModel}/{urlSP}", name="turbo_catalog_choice_city")
     */
    public function showChoiceCityPageAction(
        Request $request,
        $urlBrand,
        $urlModel,
        $urlSP,
        VariableTransformer $transformer,
        TitleProvider $titleProvider
    )
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $sparePart = $em->getRepository(SparePart::class)->findOneBy(["url" => $urlSP]);
        $brand = $em->getRepository(Brand::class)->findOneBy(["url" => $urlBrand]);
        $model = $em->getRepository(Model::class)->findOneBy(["url" => $urlModel]);

        if(!($sparePart instanceof SparePart) || !($brand instanceof Brand) || !($model instanceof Model)){
            return $this->redirect($request->headers->get('referer'));
        }

        $capitals = $em->getRepository(City::class)->findBy(
            ["type" => City::CAPITAL_TYPE],
            ["name" => "ASC"]
        );

        $othersCities = $em->getRepository(City::class)->findBy(
            ["type" => City::REGIONAL_CITY_TYPE],
            ["name" => "ASC"]
        );

        $page = $em->getRepository(CatalogTurboChoiceCity::class)->findAll()[0];
        $transformParameters = [$sparePart, $brand, $model];

        return $this->render('client/catalog/turbo/choice-city.html.twig', [
            'capitals' => $capitals,
            'otherCities' => $othersCities,
            'page' => $transformer->transformPage($page, $transformParameters),
            'sparePart' => $sparePart,
            'brand' => $brand,
            'model' => $model,
            'homepageTitle' => $titleProvider->getSinglePageTitle(MainPage::class),
            'choiceBrandTitle' => $titleProvider->getSinglePageTitle(CatalogTurboChoiceBrand::class, $transformParameters),
            'choiceModelTitle' => $titleProvider->getSinglePageTitle(CatalogTurboChoiceModel::class, $transformParameters),
            'choiceSparePartTitle' => $titleProvider->getSinglePageTitle(CatalogTurboChoiceSparePart::class, $transformParameters),
        ]);
    }

    /**
     * @Route("/{urlBrand}/{urlModel}/{urlSP}/{urlCity}", name="turbo_catalog_in_stock")
     */
    public function showCatalogFinalPageAction(
        Request $request,
        $urlSP,
        $urlBrand,
        $urlModel,
        $urlCity,
        VariableTransformer $transformer,
        TitleProvider $titleProvider
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
            return $this->redirect($request->headers->get('referer'));
        }

        $page = $em->getRepository(CatalogTurboChoiceFinalPage::class)->findAll()[0];
        $transformParameters = $city instanceof City ? [$sparePart, $brand, $model, $city] : [$sparePart, $brand, $model];

        return $this->render('client/catalog/turbo/final-page.html.twig', [
            'page' => $transformer->transformPage($page, $transformParameters),
            'sparePart' => $sparePart,
            'brand' => $brand,
            'model' => $model,
            'city' => $isAllCities ? null : $city,
            'homepageTitle' => $titleProvider->getSinglePageTitle(MainPage::class),
            'choiceBrandTitle' => $titleProvider->getSinglePageTitle(CatalogTurboChoiceBrand::class, $transformParameters),
            'choiceModelTitle' => $titleProvider->getSinglePageTitle(CatalogTurboChoiceModel::class, $transformParameters),
            'choiceSparePartTitle' => $titleProvider->getSinglePageTitle(CatalogTurboChoiceSparePart::class, $transformParameters),
            'choiceCityTitle' => $titleProvider->getSinglePageTitle(CatalogTurboChoiceCity::class, $transformParameters),
        ]);
    }
}