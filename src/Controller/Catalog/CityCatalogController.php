<?php

namespace App\Controller\Catalog;

use App\Entity\Brand;
use App\Entity\Catalog\Brand\CatalogBrandChoiceCity;
use App\Entity\Catalog\City\CatalogCityChoiceBrand;
use App\Entity\Catalog\City\CatalogCityChoiceCity;
use App\Entity\Catalog\City\CatalogCityChoiceModel;
use App\Entity\Catalog\City\CatalogCityChoiceSparePart;
use App\Entity\Catalog\City\CatalogCityChoiceYear;
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
 * @Route("/city-catalog")
 */
class CityCatalogController extends Controller
{
    /**
     * @Route("/", name="show_city_catalog_choice_city")
     */
    public function showChoiceCityPageAction(Request $request, TitleProvider $titleProvider)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $capital = $em->getRepository(City::class)->findOneBy(["type" => City::CAPITAL_TYPE]);
        $regionalCities = $em->getRepository(City::class)->findBy(["type" => City::REGIONAL_CITY_TYPE], ["name" => "ASC"]);
        $othersCities = $em->getRepository(City::class)->findBy([
            "type" => City::OTHERS_TYPE,
            "active" => true,
        ], ["name" => "ASC"]);

        $brands = $em->getRepository(Brand::class)->findBy([
            "active" => true,
            "popular" => true,
        ], ["name" => "ASC"]);

        return $this->render('client/catalog/city/choice-city.html.twig', [
            'capital' => $capital,
            'regionalCities' => $regionalCities,
            'othersCities' => $othersCities,
            'brands' => $brands,
            'page' => $em->getRepository(CatalogCityChoiceCity::class)->findAll()[0],
            'homepageTitle' => $titleProvider->getSinglePageTitle(MainPage::class),
        ]);
    }

    /**
     * @Route("/{urlCity}", name="show_city_catalog_choice_brand")
     */
    public function showChoiceBrandPageAction(
        Request $request,
        $urlCity,
        TitleProvider $titleProvider,
        VariableTransformer $transformer
    )
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $city = $em->getRepository(City::class)->findOneBy(["url" => $urlCity]);

        if(!($city instanceof City)){
            return $this->redirect($request->headers->get('referer'));
        }

        $page = $em->getRepository(CatalogCityChoiceBrand::class)->findAll()[0];

        $transformParameters = [$city];

        $brands = $em->getRepository(Brand::class)->findBy(
            [
                "active" => true,
            ],
            ["name" => "ASC"]
        );

        $popularBrandsParsed = [];
        $allBrandsParsed = [];

        /** @var Brand $brand */
        foreach ($brands as $brand){
            $pageParameters = $transformParameters;
            $pageParameters[] = $brand;
            $object = [
                "item" => $brand,
                "title" => $titleProvider->getSinglePageTitle(CatalogCityChoiceModel::class, $pageParameters),
            ];

            $allBrandsParsed[] = $object;

            if($brand->isPopular()){
                $popularBrandsParsed[] = $object;
            }
        }

        return $this->render('client/catalog/city/choice-brand.html.twig', [
            "page" => $transformer->transformPage($page, $transformParameters),
            'city' => $city,
            'homepageTitle' => $titleProvider->getSinglePageTitle(MainPage::class),
            'choiceCityTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceCity::class, $transformParameters),
            'popularBrands' => $popularBrandsParsed,
            'allBrands' => $allBrandsParsed,
        ]);
    }

    /**
     * @Route("/{urlCity}/{urlBrand}", name="show_city_catalog_choice_model")
     */
    public function showChoiceModelPageAction(
        Request $request,
        $urlCity,
        $urlBrand,
        TitleProvider $titleProvider,
        VariableTransformer $transformer
    )
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $city = $em->getRepository(City::class)->findOneBy(["url" => $urlCity]);
        $brand = $em->getRepository(Brand::class)->findOneBy(["url" => $urlBrand]);

        if(!($brand instanceof Brand) || !($city instanceof City)){
            return $this->redirect($request->headers->get('referer'));
        }

        $page = $em->getRepository(CatalogCityChoiceModel::class)->findAll()[0];

        $transformParameters = [$city, $brand];

        $models = $em->getRepository(Model::class)->findBy(
            [
                "active" => true,
                "brand" => $brand,
            ],
            ["name" => "ASC"]
        );

        $popularModelsParsed = [];
        $allModelsParsed = [];

        /** @var Model $model */
        foreach ($models as $model){
            $pageParameters = $transformParameters;
            $pageParameters[] = $model;
            $object = [
                "item" => $model,
                "title" => $titleProvider->getSinglePageTitle(CatalogCityChoiceYear::class, $pageParameters),
            ];

            $allModelsParsed[] = $object;

            if($model->isPopular()){
                $popularModelsParsed[] = $object;
            }
        }

        return $this->render('client/catalog/city/choice-model.html.twig', [
            "page" => $transformer->transformPage($page, $transformParameters),
            'city' => $city,
            'brand' => $brand,
            'homepageTitle' => $titleProvider->getSinglePageTitle(MainPage::class),
            'choiceCityTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceCity::class, $transformParameters),
            'choiceBrandTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceBrand::class, $transformParameters),
            'popularModels' => $popularModelsParsed,
            'allModels' => $allModelsParsed,
        ]);
    }

    /**
     * @Route("/{urlCity}/{urlBrand}/{urlModel}", name="show_city_catalog_choice_year")
     */
    public function showChoiceYearPageAction(
        Request $request,
        $urlCity,
        $urlBrand,
        $urlModel,
        TitleProvider $titleProvider,
        VariableTransformer $transformer
    )
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $city = $em->getRepository(City::class)->findOneBy(["url" => $urlCity]);
        $brand = $em->getRepository(Brand::class)->findOneBy(["url" => $urlBrand]);
        $model = $em->getRepository(Model::class)->findOneBy([
            "url" => $urlModel,
            "brand" => $brand,
        ]);

        if(!($brand instanceof Brand) || !($model instanceof Model) || !($city instanceof City)){
            return $this->redirect($request->headers->get('referer'));
        }

        $page = $em->getRepository(CatalogCityChoiceYear::class)->findAll()[0];

        $transformParameters = [$city, $brand, $model];

        $popularSpareParts = $em->getRepository(SparePart::class)->findBy(
            [
                "active" => true,
                "popular" => true,
            ],
            ["alternativeName1" => "ASC"]
        );

        $spareParts = [];

        foreach ($popularSpareParts as $sparePart){
            $pageParameters = $transformParameters;
            $pageParameters[] = $sparePart;

            $spareParts[] = [
                "item" => $sparePart,
                "title" => $titleProvider->getSinglePageTitle(CatalogBrandChoiceCity::class, $pageParameters),
            ];
        }

        return $this->render('client/catalog/city/choice-year.html.twig', [
            "page" => $transformer->transformPage($page, $transformParameters),
            'city' => $city,
            'brand' => $brand,
            'model' => $model,
            'homepageTitle' => $titleProvider->getSinglePageTitle(MainPage::class),
            'choiceCityTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceCity::class, $transformParameters),
            'choiceBrandTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceBrand::class, $transformParameters),
            'choiceModelTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceModel::class, $transformParameters),
            'popularSpareParts' => $spareParts,
        ]);
    }

    /**
     * @Route("/{urlCity}/{urlBrand}/{urlModel}/{year}", name="show_city_catalog_choice_spare_part")
     */
    public function showChoiceSparePartPageAction(
        Request $request,
        $urlCity,
        $urlBrand,
        $urlModel,
        $year,
        TitleProvider $titleProvider,
        VariableTransformer $transformer
    )
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $city = $em->getRepository(City::class)->findOneBy(["url" => $urlCity]);
        $brand = $em->getRepository(Brand::class)->findOneBy(["url" => $urlBrand]);
        $model = $em->getRepository(Model::class)->findOneBy([
            "url" => $urlModel,
            "brand" => $brand,
        ]);
        $year = is_numeric($year) ? (int)$year : null;

        if(!($brand instanceof Brand) || !($model instanceof Model) || !($city instanceof City) ||
            !$year || !$model->isHasYear($year)){
            return $this->redirect($request->headers->get('referer'));
        }

        $page = $em->getRepository(CatalogCityChoiceSparePart::class)->findAll()[0];

        $transformParameters = [$city, $brand, $model, [Model::YEAR_VARIABLE => $year]];

        $popularSpareParts = $em->getRepository(SparePart::class)->findBy(
            [
                "active" => true,
                "popular" => true
            ],
            ["alternativeName1" => "ASC"]
        );

        $unpopularSpareParts = $em->getRepository(SparePart::class)->findBy(
            [
                "active" => true,
                "popular" => false
            ],
            ["alternativeName1" => "ASC"]
        );

        return $this->render('client/catalog/city/choice-spare-part.html.twig', [
            "page" => $transformer->transformPage($page, $transformParameters),
            'city' => $city,
            'brand' => $brand,
            'model' => $model,
            'year' => $year,
            'homepageTitle' => $titleProvider->getSinglePageTitle(MainPage::class),
            'choiceCityTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceCity::class, $transformParameters),
            'choiceBrandTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceBrand::class, $transformParameters),
            'choiceModelTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceModel::class, $transformParameters),
            'choiceYearTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceYear::class, $transformParameters),
            'popularSpareParts' => $popularSpareParts,
            'unpopularSpareParts' => $unpopularSpareParts,
        ]);
    }
}