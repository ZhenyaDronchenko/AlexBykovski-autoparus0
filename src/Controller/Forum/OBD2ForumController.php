<?php

namespace App\Controller\Forum;

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
use App\Entity\Error\CodeOBD2Error;
use App\Entity\Error\TypeOBD2Error;
use App\Entity\Forum\OBD2Forum\OBD2ForumChoiceBrand;
use App\Entity\Forum\OBD2Forum\OBD2ForumChoiceCode;
use App\Entity\Forum\OBD2Forum\OBD2ForumChoiceModel;
use App\Entity\Forum\OBD2Forum\OBD2ForumChoiceType;
use App\Entity\Forum\OBD2Forum\OBD2ForumFinalPage;
use App\Entity\General\NotFoundPage;
use App\Entity\Model;
use App\Entity\SparePart;
use App\Transformer\VariableTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/forum-obd2")
 */
class OBD2ForumController extends Controller
{
    /**
     * @Route("", name="obd2_forum_choice_brand")
     */
    public function showChoiceBrandPageAction(Request $request)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $allBrands = $em->getRepository(Brand::class)->findBy(["active" => true], ["name" => "ASC"]);

        $popularBrands = array_filter($allBrands, function(Brand $brand){
            return $brand->isPopular();
        });

        return $this->render('client/forum/obd2-forum/choice-brand.html.twig', [
            'allBrands' => $allBrands,
            'popularBrands' => $popularBrands,
            'page' => $em->getRepository(OBD2ForumChoiceBrand::class)->findAll()[0],
        ]);
    }

    /**
     * @Route("/{urlBrand}", name="obd2_forum_choice_type")
     */
    public function showChoiceModelPageAction(Request $request, $urlBrand, VariableTransformer $transformer)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $brand = $em->getRepository(Brand::class)->findOneBy(["url" => $urlBrand]);

        if(!($brand instanceof Brand)){
            throw new NotFoundHttpException(NotFoundPage::DEFAULT_MESSAGE);
        }

//        $allModels = $em->getRepository(Model::class)->findBy([
//            "active" => true,
//            "brand" => $brand,
//        ],
//            ["name" => "ASC"]);
//
//        $popularModels = array_filter($allModels, function(Model $model){
//            return $model->isPopular();
//        });

        $page = $em->getRepository(OBD2ForumChoiceType::class)->findAll()[0];

        return $this->render('client/forum/obd2-forum/choice-type.html.twig', [
//            'allModels' => $allModels,
//            'popularModels' => $popularModels,
            'page' => $transformer->transformPage($page, [$brand]),
            'brand' => $brand,
        ]);
    }

    /**
     * @Route("/{urlBrand}/{urlType}", name="obd2_forum_choice_code")
     */
    public function showChoiceSparePartPageAction(Request $request, $urlBrand, $urlType, VariableTransformer $transformer)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $brand = $em->getRepository(Brand::class)->findOneBy(["url" => $urlBrand]);
        $type = $em->getRepository(TypeOBD2Error::class)->findOneBy(["url" => $urlType]);

        if(!($brand instanceof Brand) || !($type instanceof TypeOBD2Error)){
            throw new NotFoundHttpException(NotFoundPage::DEFAULT_MESSAGE);
        }

//        $popularSpareParts = $em->getRepository(SparePart::class)->findBy(
//            [
//                "active" => true,
//                "popular" => true
//            ],
//            ["name" => "ASC"]
//        );
//
//        $unpopularSpareParts = $em->getRepository(SparePart::class)->findBy(
//            [
//                "active" => true,
//                "popular" => false
//            ],
//            ["name" => "ASC"]
//        );

        $page = $em->getRepository(OBD2ForumChoiceCode::class)->findAll()[0];

        return $this->render('client/forum/obd2-forum/choice-code.html.twig', [
//            'popularSpareParts' => $popularSpareParts,
//            'unpopularSpareParts' => $unpopularSpareParts,
            'page' => $transformer->transformPage($page, [$brand, $type]),
            'brand' => $brand,
            'type' => $type,
        ]);
    }

    /**
     * @Route("/{urlBrand}/{urlType}/{urlCode}", name="obd2_forum_choice_model")
     */
    public function showChoiceCityPageAction(Request $request, $urlBrand, $urlType, $urlCode, VariableTransformer $transformer)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $brand = $em->getRepository(Brand::class)->findOneBy(["url" => $urlBrand]);
        $type = $em->getRepository(TypeOBD2Error::class)->findOneBy(["url" => $urlType]);
        $code = $em->getRepository(CodeOBD2Error::class)->findOneBy(["url" => $urlCode]);

        if(!($brand instanceof Brand) || !($type instanceof TypeOBD2Error) || !($code instanceof CodeOBD2Error)){
            throw new NotFoundHttpException(NotFoundPage::DEFAULT_MESSAGE);
        }
//
//        $capitals = $em->getRepository(City::class)->findBy(
//            ["type" => City::CAPITAL_TYPE],
//            ["name" => "ASC"]
//        );
//
//        $othersCities = $em->getRepository(City::class)->findBy(
//            ["type" => City::REGIONAL_CITY_TYPE],
//            ["name" => "ASC"]
//        );
//
//        $adverts = $em->getRepository(AutoSparePartGeneralAdvert::class)->findByParameters($sparePart, $brand, $model);

        $page = $em->getRepository(OBD2ForumChoiceModel::class)->findAll()[0];

        return $this->render('client/forum/obd2-forum/choice-model.html.twig', [
//            'capitals' => $capitals,
//            'otherCities' => $othersCities,
            'page' => $transformer->transformPage($page, [$brand, $type, $code]),
            'brand' => $brand,
            'type' => $type,
            'code' => $code,
//            'adverts' => $adverts,
        ]);
    }

    /**
     * @Route("/{urlBrand}/{urlType}/{urlCode}/{urlModel}", name="obd2_forum_final_page")
     */
    public function showCatalogInStockAction(Request $request, $urlBrand, $urlType, $urlCode, $urlModel, VariableTransformer $transformer)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $brand = $em->getRepository(Brand::class)->findOneBy(["url" => $urlBrand]);
        $type = $em->getRepository(TypeOBD2Error::class)->findOneBy(["url" => $urlType]);
        $code = $em->getRepository(CodeOBD2Error::class)->findOneBy(["url" => $urlCode]);
        $model = $em->getRepository(Model::class)->findOneBy([
            "url" => $urlModel,
            "brand" => $brand,
        ]);

        if(!($brand instanceof Brand) || !($model instanceof Model) ||
            !($type instanceof TypeOBD2Error) || !($code instanceof CodeOBD2Error)){
            throw new NotFoundHttpException(NotFoundPage::DEFAULT_MESSAGE);
        }

        $page = $em->getRepository(OBD2ForumFinalPage::class)->findAll()[0];
//        $transformParameters = $city instanceof City ? [$sparePart, $brand, $model, $city] : [$sparePart, $brand, $model];
//        $cityParameter = $city instanceof City ? $city : null;
//        $adverts = $em->getRepository(AutoSparePartGeneralAdvert::class)->findByParameters($sparePart, $brand, $model, $cityParameter);

        return $this->render('client/forum/obd2-forum/final-page.html.twig', [
            'page' => $transformer->transformPage($page, [$brand, $type, $code, $model]),
            'brand' => $brand,
            'type' => $type,
            'code' => $code,
            'model' => $model,
//            'adverts' => $adverts,
        ]);
    }
}