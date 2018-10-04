<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\Catalog\SparePart\CatalogSparePartChoiceBrand;
use App\Entity\Catalog\SparePart\CatalogSparePartChoiceCity;
use App\Entity\Catalog\SparePart\CatalogSparePartChoiceModel;
use App\Entity\Catalog\SparePart\CatalogSparePartChoiceSparePart;
use App\Entity\City;
use App\Entity\Model;
use App\Entity\SparePart;
use App\Transformer\VariableTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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
            'page' => $em->getRepository(CatalogSparePartChoiceSparePart::class)->findAll()[0]
        ]);
    }

    /**
     * @Route("/{url}/", name="show_spare_part_catalog_choice_brand")
     */
    public function showCatalogChoiceBrandAction(Request $request, $url, VariableTransformer $transformer)
    {
        $em = $this->getDoctrine()->getManager();
        $sparePart = $em->getRepository(SparePart::class)->findOneBy(["url" => $url]);

        if(!($sparePart instanceof SparePart)){
            return $this->redirect($request->headers->get('referer'));
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
        ]);
    }

    /**
     * @Route("/{urlSP}/{urlBrand}/", name="show_spare_part_catalog_choice_model")
     */
    public function showCatalogChoiceModelAction(Request $request, $urlSP, $urlBrand, VariableTransformer $transformer)
    {
        $em = $this->getDoctrine()->getManager();
        $sparePart = $em->getRepository(SparePart::class)->findOneBy(["url" => $urlSP]);
        $brand = $em->getRepository(Brand::class)->findOneBy(["url" => $urlBrand]);

        if(!($sparePart instanceof SparePart) || !($brand instanceof Brand)){
            return $this->redirect($request->headers->get('referer'));
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
        ]);
    }

    /**
     * @Route("/{urlSP}/{urlBrand}/{urlModel}/", name="show_spare_part_catalog_choice_city")
     */
    public function showCatalogChoiceCityAction(Request $request, $urlSP, $urlBrand, $urlModel, VariableTransformer $transformer)
    {
        $em = $this->getDoctrine()->getManager();
        $sparePart = $em->getRepository(SparePart::class)->findOneBy(["url" => $urlSP]);
        $brand = $em->getRepository(Brand::class)->findOneBy(["url" => $urlBrand]);
        $isAllModels = $urlModel === CatalogSparePartChoiceModel::ALL_MODELS_URL;
        $model = $isAllModels ? $urlModel : $em->getRepository(Model::class)->findOneBy(["url" => $urlModel]);

        if(!($sparePart instanceof SparePart) || !($brand instanceof Brand) || !($model instanceof Model) && !$isAllModels){
            return $this->redirect($request->headers->get('referer'));
        }

        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $capitals = $em->getRepository(City::class)->findBy(
            ["type" => City::CAPITAL_TYPE],
            ["name" => "ASC"]
        );

        $othersCities = $em->getRepository(City::class)->findBy(
            ["type" => [City::REGIONAL_CITY_TYPE, City::OTHERS_TYPE]],
            ["name" => "ASC"]
        );

        $page = $em->getRepository(CatalogSparePartChoiceCity::class)->findAll()[0];
        $transformParameters = $model instanceof Model ? [$sparePart, $brand, $model] : [$sparePart, $brand];

        return $this->render('client/catalog/spare-part/choice-city.html.twig', [
            'capitals' => $capitals,
            'otherCities' => $othersCities,
            'page' => $transformer->transformPage($page, $transformParameters),
        ]);
    }
}