<?php

namespace App\Controller;
use App\Entity\Admin;
use App\Entity\Advert\AutoSparePart\AutoSparePartGeneralAdvert;
use App\Entity\Brand;
use App\Entity\Catalog\Brand\CatalogBrandChoiceBrand;
use App\Entity\Catalog\Brand\CatalogBrandChoiceCity;
use App\Entity\Catalog\Brand\CatalogBrandChoiceFinalPage;
use App\Entity\Catalog\Brand\CatalogBrandChoiceInStock;
use App\Entity\Catalog\Brand\CatalogBrandChoiceModel;
use App\Entity\Catalog\Brand\CatalogBrandChoiceSparePart;
use App\Entity\City;
use App\Entity\Model;
use App\Entity\SparePart;
use App\Transformer\VariableTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/brand-catalog")
 */
class BrandCatalogController extends Controller
{
    /**
     * @Route("", name="show_brand_catalog_choice_brand")
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
            'page' => $em->getRepository(CatalogBrandChoiceBrand::class)->findAll()[0]
        ]);
    }

    /**
     * @Route("/{urlBrand}", name="show_brand_catalog_choice_model")
     */
    public function showChoiceModelPageAction(Request $request, $urlBrand, VariableTransformer $transformer)
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

        $page = $em->getRepository(CatalogBrandChoiceModel::class)->findAll()[0];

        return $this->render('client/catalog/brand/choice-model.html.twig', [
            'allModels' => $allModels,
            'popularModels' => $popularModels,
            'page' => $transformer->transformPage($page, [$brand]),
            'brand' => $brand,
        ]);
    }

    /**
     * @Route("/{urlBrand}/{urlModel}", name="show_brand_catalog_choice_spare_part")
     */
    public function showChoiceSparePartPageAction(Request $request, $urlBrand, $urlModel, VariableTransformer $transformer)
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

        $page = $em->getRepository(CatalogBrandChoiceSparePart::class)->findAll()[0];

        return $this->render('client/catalog/brand/choice-spare-part.html.twig', [
            'popularSpareParts' => $popularSpareParts,
            'unpopularSpareParts' => $unpopularSpareParts,
            'page' => $transformer->transformPage($page, [$brand, $model]),
            'brand' => $brand,
            'model' => $model,
        ]);
    }

    /**
     * @Route("/{urlBrand}/{urlModel}/{urlSP}", name="show_brand_catalog_choice_city")
     */
    public function showChoiceCityPageAction(Request $request, $urlBrand, $urlModel, $urlSP, VariableTransformer $transformer)
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

        $adverts = $em->getRepository(AutoSparePartGeneralAdvert::class)->findByParameters($sparePart, $brand, $model);

        $page = $em->getRepository(CatalogBrandChoiceCity::class)->findAll()[0];

        return $this->render('client/catalog/brand/choice-city.html.twig', [
            'capitals' => $capitals,
            'otherCities' => $othersCities,
            'page' => $transformer->transformPage($page, [$sparePart, $brand, $model]),
            'sparePart' => $sparePart,
            'brand' => $brand,
            'model' => $model,
            'adverts' => $adverts,
        ]);
    }

    /**
     * @Route("/{urlBrand}/{urlModel}/{urlSP}/{urlCity}", name="show_brand_catalog_in_stock")
     */
    public function showCatalogInStockAction(Request $request, $urlSP, $urlBrand, $urlModel, $urlCity, VariableTransformer $transformer)
    {
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

        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $page = $em->getRepository(CatalogBrandChoiceInStock::class)->findAll()[0];
        $transformParameters = $city instanceof City ? [$sparePart, $brand, $model, $city] : [$sparePart, $brand, $model];
        $cityParameter = $city instanceof City ? $city : null;
        $adverts = $em->getRepository(AutoSparePartGeneralAdvert::class)->findByParameters($sparePart, $brand, $model, $cityParameter);

        return $this->render('client/catalog/brand/only-in-stock.html.twig', [
            'page' => $transformer->transformPage($page, $transformParameters),
            'sparePart' => $sparePart,
            'brand' => $brand,
            'model' => $model,
            'adverts' => $adverts,
            'city' => $isAllCities ? null : $city,
        ]);
    }

    /**
     * @Route("/{urlBrand}/{urlModel}/{urlSP}/{urlCity}/in_stock", name="show_brand_catalog_final_page")
     */
    public function showCatalogFinalPageAction(Request $request, $urlSP, $urlBrand, $urlModel, $urlCity, VariableTransformer $transformer)
    {
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

        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $page = $em->getRepository(CatalogBrandChoiceFinalPage::class)->findAll()[0];
        $transformParameters = $city instanceof City ? [$sparePart, $brand, $model, $city] : [$sparePart, $brand, $model];

        $cityParameter = $city instanceof City ? $city : null;
        $adverts = $em->getRepository(AutoSparePartGeneralAdvert::class)->findByParameters($sparePart, $brand, $model, $cityParameter, [AutoSparePartGeneralAdvert::STOCK_TYPE_IN_STOCK]);

        return $this->render('client/catalog/brand/final-page.html.twig', [
            'page' => $transformer->transformPage($page, $transformParameters),
            'sparePart' => $sparePart,
            'brand' => $brand,
            'model' => $model,
            'adverts' => $adverts,
            'city' => $isAllCities ? null : $city,
        ]);
    }
}