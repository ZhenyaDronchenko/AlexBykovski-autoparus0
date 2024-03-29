<?php

namespace App\Controller\Catalog;

use App\Entity\Advert\AutoSparePart\AutoSparePartSpecificAdvert;
use App\Entity\Brand;
use App\Entity\Catalog\Brand\CatalogBrandChoiceCity;
use App\Entity\Catalog\City\CatalogCityChoiceBrand;
use App\Entity\Catalog\City\CatalogCityChoiceCity;
use App\Entity\Catalog\City\CatalogCityChoiceEngineCapacity;
use App\Entity\Catalog\City\CatalogCityChoiceEngineType;
use App\Entity\Catalog\City\CatalogCityChoiceFinalPage;
use App\Entity\Catalog\City\CatalogCityChoiceInStock;
use App\Entity\Catalog\City\CatalogCityChoiceModel;
use App\Entity\Catalog\City\CatalogCityChoiceSparePart;
use App\Entity\Catalog\City\CatalogCityChoiceSparePartStatus;
use App\Entity\Catalog\City\CatalogCityChoiceVehicleType;
use App\Entity\Catalog\City\CatalogCityChoiceYear;
use App\Entity\City;
use App\Entity\Client\Client;
use App\Entity\Engine;
use App\Entity\EngineType;
use App\Entity\General\MainPage;
use App\Entity\General\NotFoundPage;
use App\Entity\Model;
use App\Entity\Request\CityCatalogRequest;
use App\Entity\Request\SparePartRequest;
use App\Entity\SparePart;
use App\Entity\SparePartCondition;
use App\Entity\VehicleType;
use App\Form\Type\Request\CityCatalogRequestFormType;
use App\Helper\CityCatalogHelper;
use App\Provider\TitleProvider;
use App\Transformer\VariableTransformer;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

//http://localhost:8001/city-catalog/minsk/bmw/x5_e70/2007/akb/petrol/3_0/suv/new
class CityCatalogController extends Controller
{
    /**
     * @Route("/city-catalog/", name="show_city_catalog_choice_city")
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
        ]);
    }

    /**
     * @Route("/city-catalog/{urlCity}", name="show_city_catalog_choice_brand")
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

        $allBrands = $em->getRepository(Brand::class)->findBy(["active" => true], ["name" => "ASC"]);

        $popularBrands = array_filter($allBrands, function(Brand $brand){
            return $brand->isPopular();
        });

        return $this->render('client/catalog/city/choice-brand.html.twig', [
            "page" => $transformer->transformPage($page, $transformParameters),
            'city' => $city,
            'choiceCityTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceCity::class, $transformParameters),
            'popularBrands' => $popularBrands,
            'allBrands' => $allBrands,
        ]);
    }

    /**
     * @Route("/city-catalog/{urlCity}/{urlBrand}", name="show_city_catalog_choice_model")
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
            'choiceCityTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceCity::class, $transformParameters),
            'choiceBrandTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceBrand::class, $transformParameters),
            'popularModels' => $popularModelsParsed,
            'allModels' => $allModelsParsed,
        ]);
    }

    /**
     * @Route("/city-catalog/{urlCity}/{urlBrand}/{urlModel}", name="show_city_catalog_choice_year")
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

        return $this->render('client/catalog/city/choice-year.html.twig', [
            "page" => $transformer->transformPage($page, $transformParameters),
            'city' => $city,
            'brand' => $brand,
            'model' => $model,
            'choiceCityTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceCity::class, $transformParameters),
            'choiceBrandTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceBrand::class, $transformParameters),
            'choiceModelTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceModel::class, $transformParameters),
            'popularSpareParts' => $popularSpareParts,
        ]);
    }

    /**
     * @Route("/city-catalog/{urlCity}/{urlBrand}/{urlModel}/{year}", name="show_city_catalog_choice_spare_part")
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
            'choiceCityTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceCity::class, $transformParameters),
            'choiceBrandTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceBrand::class, $transformParameters),
            'choiceModelTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceModel::class, $transformParameters),
            'choiceYearTitle' => $titleProvider->getSinglePageTitle(CatalogCityChoiceYear::class, $transformParameters),
            'popularSpareParts' => $popularSpareParts,
            'unpopularSpareParts' => $unpopularSpareParts,
        ]);
    }

    /**
     * @Route("/city-catalog/{urlCity}/{urlBrand}/{urlModel}/{year}/{urlSP}", name="show_city_catalog_choice_engine_type")
     */
    public function showChoiceEngineTypePageAction(
        Request $request,
        $urlCity,
        $urlBrand,
        $urlModel,
        $year,
        $urlSP,
        VariableTransformer $transformer
    )
    {
        /** @var RouterInterface $router */
        $router = $this->get('router');
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

        $engineTypes = $model->getTechnicalData()->getEngineTypes();

        if($router->match(parse_url($request->headers->get('referer'))["path"])['_route'] === "show_city_catalog_choice_spare_part" &&
            $engineTypes->count() === 1){
            return $this->redirectToRoute("show_city_catalog_choice_engine_capacity",
                array_merge($request->attributes->get('_route_params'), ["urlET" => $engineTypes->first()->getUrl()]));
        }

        return $this->render('client/catalog/city/choice-engine-type.html.twig', [
            "page" => $transformer->transformPage($page, $transformParameters),
            'city' => $city,
            'brand' => $brand,
            'model' => $model,
            'year' => $year,
            'sparePart' => $sparePart,
            'engineTypes' => $engineTypes,
        ]);
    }

    /**
     * @Route("/city-catalog/{urlCity}/{urlBrand}/{urlModel}/{year}/{urlSP}/{urlET}", name="show_city_catalog_choice_engine_capacity")
     */
    public function showChoiceEngineCapacityPageAction(
        Request $request,
        $urlCity,
        $urlBrand,
        $urlModel,
        $year,
        $urlSP,
        $urlET,
        VariableTransformer $transformer
    )
    {
        /** @var RouterInterface $router */
        $router = $this->get('router');
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

        $engines = $model->getTechnicalData()->getEnginesByType($engineType->getType());

        if($router->match(parse_url($request->headers->get('referer'))["path"])['_route'] === "show_city_catalog_choice_engine_type" &&
            count($engines) === 1){
            return $this->redirectToRoute("show_city_catalog_choice_vehicle_type",
                array_merge($request->attributes->get('_route_params'), ["engineId" => array_values($engines)[0]->getId()]));
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
            'engineType' => $engineType,
            'engines' => $engines,
        ]);
    }

    /**
     * @Route("/city-catalog/{urlCity}/{urlBrand}/{urlModel}/{year}/{urlSP}/{urlET}/{engineId}", name="show_city_catalog_choice_vehicle_type")
     */
    public function showChoiceVehicleTypePageAction(
        Request $request,
        $urlCity,
        $urlBrand,
        $urlModel,
        $year,
        $urlSP,
        $urlET,
        $engineId,
        VariableTransformer $transformer
    )
    {
        /** @var RouterInterface $router */
        $router = $this->get('router');
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
        $engine = $engineType ? $em->getRepository(Engine::class)->find($engineId) : null;

        if(!($brand instanceof Brand) || !($model instanceof Model) || !($city instanceof City) ||
            !$year || !$model->isHasYear($year) || !($sparePart instanceof SparePart) ||
            !($engineType instanceof EngineType) || !($engine instanceof Engine) ||
            $engine->getType() !== $engineType->getType()){
            throw new NotFoundHttpException(NotFoundPage::DEFAULT_MESSAGE);
        }

        $page = $em->getRepository(CatalogCityChoiceVehicleType::class)->findAll()[0];
        $vehicleTypes = $model->getTechnicalData()->getVehicleTypes();

        if($router->match(parse_url($request->headers->get('referer'))["path"])['_route'] === "show_city_catalog_choice_engine_capacity" &&
            $vehicleTypes->count() === 1){
            return $this->redirectToRoute("show_city_catalog_choice_spare_part_status",
                array_merge($request->attributes->get('_route_params'), ["urlVT" => $vehicleTypes->first()->getUrl()]));
        }

        $transformParameters = [$city, $brand, $model, [Model::YEAR_VARIABLE => $year], $sparePart, $engine];

        return $this->render('client/catalog/city/choice-vehicle-type.html.twig', [
            "page" => $transformer->transformPage($page, $transformParameters),
            'city' => $city,
            'brand' => $brand,
            'model' => $model,
            'year' => $year,
            'engineType' => $engineType,
            'engine' => $engine,
            'sparePart' => $sparePart,
            'vehicleTypes' => $vehicleTypes,
        ]);
    }

    /**
     * @Route("/city-catalog/{urlCity}/{urlBrand}/{urlModel}/{year}/{urlSP}/{urlET}/{engineId}/{urlVT}", name="show_city_catalog_choice_spare_part_status")
     */
    public function showChoiceSparePartStatusPageAction(
        Request $request,
        $urlCity,
        $urlBrand,
        $urlModel,
        $year,
        $urlSP,
        $urlET,
        $engineId,
        $urlVT,
        VariableTransformer $transformer
    )
    {
        /** @var RouterInterface $router */
        $router = $this->get('router');
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
        $engine = $engineType ? $em->getRepository(Engine::class)->find($engineId) : null;
        $vehicleType = $em->getRepository(VehicleType::class)->findOneBy(["url" => $urlVT]);

        if(!($brand instanceof Brand) || !($model instanceof Model) || !($city instanceof City) ||
            !$year || !$model->isHasYear($year) || !($sparePart instanceof SparePart) ||
            !($engineType instanceof EngineType) || !($engine instanceof Engine) ||
            $engine->getType() !== $engineType->getType() ||
            !($vehicleType instanceof VehicleType) || !$model->hasVehicleType($vehicleType->getType())){
            throw new NotFoundHttpException(NotFoundPage::DEFAULT_MESSAGE);
        }

        $page = $em->getRepository(CatalogCityChoiceSparePartStatus::class)->findAll()[0];
        $conditions = array_values($sparePart->getActiveConditions());
        $urlConditions = array_flip(SparePartCondition::$conditions);

        if($router->match(parse_url($request->headers->get('referer'))["path"])['_route'] === "show_city_catalog_choice_vehicle_type" &&
            count($conditions) === 1){
            $url = $urlConditions[$conditions[0]->getDescription()];

            return $this->redirectToRoute("show_city_catalog_choice_tender",
                array_merge($request->attributes->get('_route_params'), ["statusSP" => $url]));
        }

        $transformParameters = [$city, $brand, $model, [Model::YEAR_VARIABLE => $year], $sparePart, $engine,
            $vehicleType];

        return $this->render('client/catalog/city/choice-spare-part-status.html.twig', [
            "page" => $transformer->transformPage($page, $transformParameters),
            'city' => $city,
            'brand' => $brand,
            'model' => $model,
            'year' => $year,
            'sparePart' => $sparePart,
            'engineType' => $engineType,
            'engine' => $engine,
            'vehicleType' => $vehicleType,
            'conditions' => $conditions,
            'urlConditions' => $urlConditions
        ]);
    }

    /**
     * @Route("/city-catalog/{urlCity}/{urlBrand}/{urlModel}/{year}/{urlSP}/{urlET}/{engineId}/{urlVT}/{statusSP}", name="show_city_catalog_choice_in_stock")
     */
    public function showChoiceInStockPageAction(
        Request $request,
        $urlCity,
        $urlBrand,
        $urlModel,
        $year,
        $urlSP,
        $urlET,
        $engineId,
        $urlVT,
        $statusSP,
        VariableTransformer $transformer
    )
    {
        $urlConditions = SparePartCondition::$conditions;
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
        $engine = $engineType ? $em->getRepository(Engine::class)->find($engineId) : null;
        $vehicleType = $em->getRepository(VehicleType::class)->findOneBy(["url" => $urlVT]);
        $condition = $sparePart && array_key_exists($statusSP, $urlConditions)
            ? $em->getRepository(SparePartCondition::class)->findOneBy([
            "sparePart" => $sparePart,
            "description" => $urlConditions[$statusSP],
            "isActive" => true,
        ]) : null;

        if(!($brand instanceof Brand) || !($model instanceof Model) || !($city instanceof City) ||
            !$year || !$model->isHasYear($year) || !($sparePart instanceof SparePart) ||
            !($engineType instanceof EngineType) || !($engine instanceof Engine) ||
            $engine->getType() !== $engineType->getType() ||
            !($vehicleType instanceof VehicleType) || !$model->hasVehicleType($vehicleType->getType()) ||
            !($condition instanceof SparePartCondition)){
            throw new NotFoundHttpException(NotFoundPage::DEFAULT_MESSAGE);
        }

        $page = $em->getRepository(CatalogCityChoiceInStock::class)->findAll()[0];

        $transformParameters = [$city, $brand, $model, [Model::YEAR_VARIABLE => $year], $sparePart, $engine,
            $vehicleType, $condition, [SparePartCondition::ZAP_CONDITION => $condition->getSingleAdjective()]];

        return $this->render('client/catalog/city/choice-in-stock.html.twig', [
            "page" => $transformer->transformPage($page, $transformParameters),
            'city' => $city,
            'brand' => $brand,
            'model' => $model,
            'year' => $year,
            'sparePart' => $sparePart,
            'engineType' => $engineType,
            'engine' => $engine,
            'vehicleType' => $vehicleType,
            'condition' => $condition,
        ]);
    }

    /**
     * @Route("/city-catalog/{urlCity}/{urlBrand}/{urlModel}/{year}/{urlSP}/{urlET}/{engineId}/{urlVT}/{statusSP}/{inStockType}", name="show_city_catalog_choice_final_page")
     */
    public function showChoiceFinalPagePageAction(
        Request $request,
        $urlCity,
        $urlBrand,
        $urlModel,
        $year,
        $urlSP,
        $urlET,
        $engineId,
        $urlVT,
        $statusSP,
        $inStockType,
        VariableTransformer $transformer
    )
    {
        $urlConditions = SparePartCondition::$conditions;
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
        $engine = $engineType ? $em->getRepository(Engine::class)->find($engineId) : null;
        $vehicleType = $em->getRepository(VehicleType::class)->findOneBy(["url" => $urlVT]);
        $condition = $sparePart && array_key_exists($statusSP, $urlConditions)
            ? $em->getRepository(SparePartCondition::class)->findOneBy([
                "sparePart" => $sparePart,
                "description" => $urlConditions[$statusSP],
                "isActive" => true,
            ]) : null;

        if(!($brand instanceof Brand) || !($model instanceof Model) || !($city instanceof City) ||
            !$year || !$model->isHasYear($year) || !($sparePart instanceof SparePart) ||
            !($engineType instanceof EngineType) || !($engine instanceof Engine) ||
            $engine->getType() !== $engineType->getType() ||
            !($vehicleType instanceof VehicleType) || !$model->hasVehicleType($vehicleType->getType()) ||
            !($condition instanceof SparePartCondition) ||
            !(array_key_exists($inStockType, AutoSparePartSpecificAdvert::STOCK_TYPES_CLIENT_VIEW))){
            throw new NotFoundHttpException(NotFoundPage::DEFAULT_MESSAGE);
        }

        $page = $em->getRepository(CatalogCityChoiceFinalPage::class)->findAll()[0];
        $stockValue = AutoSparePartSpecificAdvert::STOCK_TYPES_CLIENT_VIEW[$inStockType];

        $transformParameters = [$city, $brand, $model, [Model::YEAR_VARIABLE => $year], $sparePart, $engine,
            $vehicleType, $condition, [SparePartCondition::ZAP_CONDITION => $condition->getSingleAdjective()],
            [AutoSparePartSpecificAdvert::STOCK_VAR => mb_strtolower($stockValue)]];

        return $this->render('client/catalog/city/choice-final-page.html.twig', [
            "page" => $transformer->transformPage($page, $transformParameters),
            'city' => $city,
            'brand' => $brand,
            'model' => $model,
            'year' => $year,
            'sparePart' => $sparePart,
            'engineType' => $engineType,
            'engine' => $engine,
            'vehicleType' => $vehicleType,
            'condition' => $condition,
            'inStockType' => $inStockType,
        ]);
    }

    /**
     * @Route("/city-catalog-ajax/handle-request", name="ajax_city_catalog_handle_request", options={"expose"=true})
     */
    public function ajaxCityCatalogHandleRequestAction(Request $request, CityCatalogHelper $helper)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        /** @var RouterInterface $router */
        $router = $this->get('router');
        /** @var Client $client */
        $client = $this->getUser();
        $isValid = false;
        $userSave = "";
        $isNewUser = false;
        $isBYPhone = true;
        $routeReferrer = $router->match(parse_url($request->headers->get('referer'))["path"])['_route'];

        $referrer = $request->headers->get('referer');
        $urlSP = $router->match(parse_url($referrer)["path"])['urlSP'];
        $sparePartDefault = $em->getRepository(SparePart::class)->findOneBy(["url" => $urlSP]);

        $cityCatalogRequest = new CityCatalogRequest();
        $cityCatalogRequest->setClient($client);

        $form = $this->createForm(CityCatalogRequestFormType::class, $cityCatalogRequest);

        if($routeReferrer !== "show_city_catalog_choice_final_page"){
            return $this->renderAjaxCityCatalogHandleRequestAction($form, $isValid, $sparePartDefault);
        }

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $phoneBY = $cityCatalogRequest->getPhoneBY();
            $phoneRU = $cityCatalogRequest->getPhoneRU();
            $cityCatalogRequest->setUrl($referrer);

            $helper->addSparePartRequests($cityCatalogRequest, $form);

            if($form->getErrors(true, false)->count() < 1) {
                if ($client) {
                    $cityCatalogRequest->setPhoneBY($client->getPhone());
                    $cityCatalogRequest->setEmail($client->getEmail());
                }

                if (!$phoneBY && !$phoneRU && !$client) {
                    $form->get("phoneBY")->addError(new FormError("Введите телефон"));

                    return $this->renderAjaxCityCatalogHandleRequestAction($form, $isValid, $sparePartDefault);
                }

                if (!$client) {
                    $user = $helper->getExistUser($cityCatalogRequest);
                    $cityCatalogRequest->setClient($user["user"]);
                    $userSave = $user["from"];

                    if (!$user) {
                        $user = $helper->getNewUser($cityCatalogRequest);
                        $cityCatalogRequest->setClient($user["user"]);
                        $userSave = $user["from"];
                        $isNewUser = true;
                        $isBYPhone = strpos($user["user"]->getPhone(), "+375") === 0;
                    }
                }

                $em->persist($cityCatalogRequest);
                $em->flush();

                $isValid = true;
            }
        }
        elseif (!$form->isSubmitted()){
            $helper->addDefaultSparePart($cityCatalogRequest, $sparePartDefault);

            $form = $this->createForm(CityCatalogRequestFormType::class, $cityCatalogRequest);

            $form->get("sparePartRequests")[0]->get("sparePartText")->setData($sparePartDefault->getName());
        }

        return $this->render('client/catalog/city/parts/final-page-form.html.twig', [
            "form" => $form->createView(),
            "isValid" => $isValid,
            "sparePart" => $sparePartDefault,
            "userSave" => $userSave,
            "isNewUser" => $isNewUser,
            "isBYPhone" => $isBYPhone
        ]);
    }

    private function renderAjaxCityCatalogHandleRequestAction(FormInterface $form, $isValid, $sparePartDefault)
    {
        return $this->render('client/catalog/city/parts/final-page-form.html.twig', [
            "form" => $form->createView(),
            "isValid" => $isValid,
            "sparePart" => $sparePartDefault
        ]);
    }
}