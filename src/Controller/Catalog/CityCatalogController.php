<?php

namespace App\Controller\Catalog;

use App\Entity\Brand;
use App\Entity\Catalog\Brand\CatalogBrandChoiceCity;
use App\Entity\Catalog\City\CatalogCityChoiceBrand;
use App\Entity\Catalog\City\CatalogCityChoiceCity;
use App\Entity\Catalog\City\CatalogCityChoiceEngineCapacity;
use App\Entity\Catalog\City\CatalogCityChoiceEngineType;
use App\Entity\Catalog\City\CatalogCityChoiceInStock;
use App\Entity\Catalog\City\CatalogCityChoiceModel;
use App\Entity\Catalog\City\CatalogCityChoiceSparePart;
use App\Entity\Catalog\City\CatalogCityChoiceSparePartStatus;
use App\Entity\Catalog\City\CatalogCityChoiceVehicleType;
use App\Entity\Catalog\City\CatalogCityChoiceYear;
use App\Entity\City;
use App\Entity\Engine;
use App\Entity\EngineType;
use App\Entity\General\MainPage;
use App\Entity\General\NotFoundPage;
use App\Entity\Model;
use App\Entity\SparePart;
use App\Entity\SparePartCondition;
use App\Entity\VehicleType;
use App\Provider\TitleProvider;
use App\Transformer\VariableTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

//http://localhost:8001/city-catalog/minsk/bmw/x5_e70/2007/akb/petrol/3_0/suv/new
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
            throw new NotFoundHttpException(NotFoundPage::DEFAULT_MESSAGE);
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
            throw new NotFoundHttpException(NotFoundPage::DEFAULT_MESSAGE);
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
            throw new NotFoundHttpException(NotFoundPage::DEFAULT_MESSAGE);
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
            throw new NotFoundHttpException(NotFoundPage::DEFAULT_MESSAGE);
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

    /**
     * @Route("/{urlCity}/{urlBrand}/{urlModel}/{year}/{urlSP}", name="show_city_catalog_choice_engine_type")
     */
    public function showChoiceEngineTypePageAction(
        Request $request,
        $urlCity,
        $urlBrand,
        $urlModel,
        $year,
        $urlSP,
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
        $sparePart = $em->getRepository(SparePart::class)->findOneBy(["url" => $urlSP]);

        if(!($brand instanceof Brand) || !($model instanceof Model) || !($city instanceof City) ||
            !$year || !$model->isHasYear($year) || !($sparePart instanceof SparePart)){
            throw new NotFoundHttpException(NotFoundPage::DEFAULT_MESSAGE);
        }

        $page = $em->getRepository(CatalogCityChoiceEngineType::class)->findAll()[0];

        $transformParameters = [$city, $brand, $model, [Model::YEAR_VARIABLE => $year], $sparePart];

        return $this->render('client/catalog/city/choice-engine-type.html.twig', [
            "page" => $transformer->transformPage($page, $transformParameters),
            'city' => $city,
            'brand' => $brand,
            'model' => $model,
            'year' => $year,
            'sparePart' => $sparePart,
            'homepageTitle' => $titleProvider->getSinglePageTitle(MainPage::class),
            'choiceCityTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceCity::class, $transformParameters),
            'choiceBrandTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceBrand::class, $transformParameters),
            'choiceModelTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceModel::class, $transformParameters),
            'choiceYearTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceYear::class, $transformParameters),
            'choiceSparePartTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceSparePart::class, $transformParameters),
        ]);
    }

    /**
     * @Route("/{urlCity}/{urlBrand}/{urlModel}/{year}/{urlSP}/{urlET}", name="show_city_catalog_choice_engine_capacity")
     */
    public function showChoiceEngineCapacityPageAction(
        Request $request,
        $urlCity,
        $urlBrand,
        $urlModel,
        $year,
        $urlSP,
        $urlET,
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
        $sparePart = $em->getRepository(SparePart::class)->findOneBy(["url" => $urlSP]);
        $engineType = $em->getRepository(EngineType::class)->findOneBy(["url" => $urlET]);

        if(!($brand instanceof Brand) || !($model instanceof Model) || !($city instanceof City) ||
            !$year || !$model->isHasYear($year) || !($sparePart instanceof SparePart) ||
            !($engineType instanceof EngineType)){
            throw new NotFoundHttpException(NotFoundPage::DEFAULT_MESSAGE);
        }

        $page = $em->getRepository(CatalogCityChoiceEngineCapacity::class)->findAll()[0];

        $transformParameters = [$city, $brand, $model, [Model::YEAR_VARIABLE => $year], $sparePart, $engineType];

        return $this->render('client/catalog/city/choice-engine-capacity.html.twig', [
            "page" => $transformer->transformPage($page, $transformParameters),
            'city' => $city,
            'brand' => $brand,
            'model' => $model,
            'year' => $year,
            'sparePart' => $sparePart,
            'homepageTitle' => $titleProvider->getSinglePageTitle(MainPage::class),
            'choiceCityTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceCity::class, $transformParameters),
            'choiceBrandTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceBrand::class, $transformParameters),
            'choiceModelTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceModel::class, $transformParameters),
            'choiceYearTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceYear::class, $transformParameters),
            'choiceSparePartTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceSparePart::class, $transformParameters),
            'choiceEngineTypeTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceEngineType::class, $transformParameters),
        ]);
    }

    /**
     * @Route("/{urlCity}/{urlBrand}/{urlModel}/{year}/{urlSP}/{urlET}/{capacity}", name="show_city_catalog_choice_vehicle_type")
     */
    public function showChoiceVehicleTypePageAction(
        Request $request,
        $urlCity,
        $urlBrand,
        $urlModel,
        $year,
        $urlSP,
        $urlET,
        $capacity,
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
        $sparePart = $em->getRepository(SparePart::class)->findOneBy(["url" => $urlSP]);
        $engineType = $em->getRepository(EngineType::class)->findOneBy(["url" => $urlET]);
        $engine = $engineType ? $em->getRepository(Engine::class)->findOneBy([
            "type" => $engineType->getType(),
            "capacity" => str_replace("_", ".", $capacity),
        ]) : null;

        if(!($brand instanceof Brand) || !($model instanceof Model) || !($city instanceof City) ||
            !$year || !$model->isHasYear($year) || !($sparePart instanceof SparePart) ||
            !($engineType instanceof EngineType) || !($engine instanceof Engine)){
            throw new NotFoundHttpException(NotFoundPage::DEFAULT_MESSAGE);
        }

        $page = $em->getRepository(CatalogCityChoiceVehicleType::class)->findAll()[0];

        $transformParameters = [$city, $brand, $model, [Model::YEAR_VARIABLE => $year], $sparePart, $engine];

        return $this->render('client/catalog/city/choice-vehicle-type.html.twig', [
            "page" => $transformer->transformPage($page, $transformParameters),
            'city' => $city,
            'brand' => $brand,
            'model' => $model,
            'year' => $year,
            'sparePart' => $sparePart,
            'homepageTitle' => $titleProvider->getSinglePageTitle(MainPage::class),
            'choiceCityTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceCity::class, $transformParameters),
            'choiceBrandTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceBrand::class, $transformParameters),
            'choiceModelTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceModel::class, $transformParameters),
            'choiceYearTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceYear::class, $transformParameters),
            'choiceSparePartTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceSparePart::class, $transformParameters),
            'choiceEngineCapacityTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceEngineCapacity::class, $transformParameters),
        ]);
    }

    /**
     * @Route("/{urlCity}/{urlBrand}/{urlModel}/{year}/{urlSP}/{urlET}/{capacity}/{urlVT}", name="show_city_catalog_choice_spare_part_status")
     */
    public function showChoiceSparePartStatusPageAction(
        Request $request,
        $urlCity,
        $urlBrand,
        $urlModel,
        $year,
        $urlSP,
        $urlET,
        $capacity,
        $urlVT,
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
        $sparePart = $em->getRepository(SparePart::class)->findOneBy(["url" => $urlSP]);
        $engineType = $em->getRepository(EngineType::class)->findOneBy(["url" => $urlET]);
        $engine = $engineType ? $em->getRepository(Engine::class)->findOneBy([
            "type" => $engineType->getType(),
            "capacity" => str_replace("_", ".", $capacity),
        ]) : null;
        $vehicleType = $em->getRepository(VehicleType::class)->findOneBy(["url" => $urlVT]);

        if(!($brand instanceof Brand) || !($model instanceof Model) || !($city instanceof City) ||
            !$year || !$model->isHasYear($year) || !($sparePart instanceof SparePart) ||
            !($engineType instanceof EngineType) || !($engine instanceof Engine) ||
            !($vehicleType instanceof VehicleType) || !$model->hasVehicleType($vehicleType->getType())){
            throw new NotFoundHttpException(NotFoundPage::DEFAULT_MESSAGE);
        }

        $page = $em->getRepository(CatalogCityChoiceSparePartStatus::class)->findAll()[0];

        $transformParameters = [$city, $brand, $model, [Model::YEAR_VARIABLE => $year], $sparePart, $engine,
            $vehicleType];

        return $this->render('client/catalog/city/choice-spare-part-status.html.twig', [
            "page" => $transformer->transformPage($page, $transformParameters),
            'city' => $city,
            'brand' => $brand,
            'model' => $model,
            'year' => $year,
            'sparePart' => $sparePart,
            'homepageTitle' => $titleProvider->getSinglePageTitle(MainPage::class),
            'choiceCityTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceCity::class, $transformParameters),
            'choiceBrandTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceBrand::class, $transformParameters),
            'choiceModelTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceModel::class, $transformParameters),
            'choiceYearTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceYear::class, $transformParameters),
            'choiceSparePartTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceSparePart::class, $transformParameters),
            'choiceEngineCapacityTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceEngineCapacity::class, $transformParameters),
            'choiceVehicleTypeTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceVehicleType::class, $transformParameters),
        ]);
    }

    /**
     * @Route("/{urlCity}/{urlBrand}/{urlModel}/{year}/{urlSP}/{urlET}/{capacity}/{urlVT}/{statusSP}", name="show_city_catalog_choice_tender")
     */
    public function showChoiceTenderPageAction(
        Request $request,
        $urlCity,
        $urlBrand,
        $urlModel,
        $year,
        $urlSP,
        $urlET,
        $capacity,
        $urlVT,
        $statusSP,
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
        $sparePart = $em->getRepository(SparePart::class)->findOneBy(["url" => $urlSP]);
        $engineType = $em->getRepository(EngineType::class)->findOneBy(["url" => $urlET]);
        $engine = $engineType ? $em->getRepository(Engine::class)->findOneBy([
            "type" => $engineType->getType(),
            "capacity" => str_replace("_", ".", $capacity),
        ]) : null;
        $vehicleType = $em->getRepository(VehicleType::class)->findOneBy(["url" => $urlVT]);
        $condition = $sparePart && array_key_exists($statusSP, SparePartCondition::$conditions)
            ? $em->getRepository(SparePartCondition::class)->findOneBy([
            "sparePart" => $sparePart,
            "description" => SparePartCondition::$conditions[$statusSP],
            "isActive" => true,
        ]) : null;

        if(!($brand instanceof Brand) || !($model instanceof Model) || !($city instanceof City) ||
            !$year || !$model->isHasYear($year) || !($sparePart instanceof SparePart) ||
            !($engineType instanceof EngineType) || !($engine instanceof Engine) ||
            !($vehicleType instanceof VehicleType) || !$model->hasVehicleType($vehicleType->getType()) ||
            !($condition instanceof SparePartCondition)){
            throw new NotFoundHttpException(NotFoundPage::DEFAULT_MESSAGE);
        }

        $page = $em->getRepository(CatalogCityChoiceInStock::class)->findAll()[0];

        $transformParameters = [$city, $brand, $model, [Model::YEAR_VARIABLE => $year], $sparePart, $engine,
            $vehicleType, $condition];

        return $this->render('client/catalog/city/choice-tender.html.twig', [
            "page" => $transformer->transformPage($page, $transformParameters),
            'city' => $city,
            'brand' => $brand,
            'model' => $model,
            'year' => $year,
            'sparePart' => $sparePart,
            'homepageTitle' => $titleProvider->getSinglePageTitle(MainPage::class),
            'choiceCityTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceCity::class, $transformParameters),
            'choiceBrandTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceBrand::class, $transformParameters),
            'choiceModelTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceModel::class, $transformParameters),
            'choiceYearTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceYear::class, $transformParameters),
            'choiceSparePartTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceSparePart::class, $transformParameters),
            'choiceEngineCapacityTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceEngineCapacity::class, $transformParameters),
            'choiceVehicleTypeTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceVehicleType::class, $transformParameters),
            'choiceSparePartStatusTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceSparePartStatus::class, $transformParameters),
        ]);
    }

    /**
     * @Route("/{urlCity}/{urlBrand}/{urlModel}/{year}/{urlSP}/{urlET}/{capacity}/{urlVT}/{statusSP}/tender", name="show_city_catalog_choice_final_page")
     */
    public function showChoiceFinalPagePageAction(
        Request $request,
        $urlCity,
        $urlBrand,
        $urlModel,
        $year,
        $urlSP,
        $urlET,
        $capacity,
        $urlVT,
        $statusSP,
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
        $sparePart = $em->getRepository(SparePart::class)->findOneBy(["url" => $urlSP]);
        $engineType = $em->getRepository(EngineType::class)->findOneBy(["url" => $urlET]);
        $engine = $engineType ? $em->getRepository(Engine::class)->findOneBy([
            "type" => $engineType->getType(),
            "capacity" => $capacity,
        ]) : null;
        $vehicleType = $em->getRepository(VehicleType::class)->findOneBy(["url" => $urlVT]);
        $condition = $sparePart && array_key_exists($statusSP, SparePartCondition::$conditions)
            ? $em->getRepository(SparePartCondition::class)->findOneBy([
                "sparePart" => $sparePart,
                "description" => $sparePart,
                "isActive" => true,
            ]) : null;

        if(!($brand instanceof Brand) || !($model instanceof Model) || !($city instanceof City) ||
            !$year || !$model->isHasYear($year) || !($sparePart instanceof SparePart) ||
            !($engineType instanceof EngineType) || !($engine instanceof Engine) ||
            !($vehicleType instanceof VehicleType) || !$model->hasVehicleType($vehicleType->getType()) ||
            !($condition instanceof SparePartCondition)){
            throw new NotFoundHttpException(NotFoundPage::DEFAULT_MESSAGE);
        }

        $page = $em->getRepository(CatalogCityChoiceInStock::class)->findAll()[0];

        $transformParameters = [$city, $brand, $model, [Model::YEAR_VARIABLE => $year], $sparePart, $engine,
            $vehicleType, $condition];

        return $this->render('client/catalog/city/choice-final-page.html.twig', [
            "page" => $transformer->transformPage($page, $transformParameters),
            'city' => $city,
            'brand' => $brand,
            'model' => $model,
            'year' => $year,
            'sparePart' => $sparePart,
            'homepageTitle' => $titleProvider->getSinglePageTitle(MainPage::class),
            'choiceCityTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceCity::class, $transformParameters),
            'choiceBrandTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceBrand::class, $transformParameters),
            'choiceModelTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceModel::class, $transformParameters),
            'choiceYearTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceYear::class, $transformParameters),
            'choiceSparePartTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceSparePart::class, $transformParameters),
            'choiceEngineCapacityTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceEngineCapacity::class, $transformParameters),
            'choiceVehicleTypeTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceVehicleType::class, $transformParameters),
            'choiceSparePartStatusTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceSparePartStatus::class, $transformParameters),
        ]);
    }
}