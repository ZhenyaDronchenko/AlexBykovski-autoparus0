<?php

namespace App\Controller\Phone;

use App\Entity\City;
use App\Entity\Phone\Catalog\SparePart\CatalogPhoneSparePartChoiceCity;
use App\Entity\Phone\Catalog\SparePart\CatalogPhoneSparePartChoicePhoneBrand;
use App\Entity\Phone\Catalog\SparePart\CatalogPhoneSparePartChoicePhoneModel;
use App\Entity\Phone\Catalog\SparePart\CatalogPhoneSparePartChoicePhoneSparePart;
use App\Entity\Phone\Catalog\SparePart\CatalogPhoneSparePartFinalPage;
use App\Entity\Phone\PhoneBrand;
use App\Entity\Phone\PhoneModel;
use App\Entity\Phone\PhoneSparePart;
use App\Transformer\VariableTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/phone_zapchasti")
 */
class PhoneSparePartCatalogController extends Controller
{
    /**
     * @Route("/", name="show_phone_spare_part_catalog_choice_phone_brand")
     */
    public function showChoiceBrandAction(Request $request)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $allBrands = $em->getRepository(PhoneBrand::class)->findBy(["active" => true], ["brandEn" => "ASC"]);

        $popularBrands = array_filter($allBrands, function(PhoneBrand $brand){
            return $brand->isPopular();
        });

        return $this->render('client/phone/catalog/spare-part/choice-brand.html.twig', [
            'allBrands' => $allBrands,
            'popularBrands' => $popularBrands,
            'page' => $em->getRepository(CatalogPhoneSparePartChoicePhoneBrand::class)->findAll()[0],
        ]);
    }

    /**
     * @Route("/{urlBrand}/", name="show_phone_spare_part_catalog_choice_phone_model")
     */
    public function showCatalogChoiceModelAction(Request $request, $urlBrand, VariableTransformer $transformer)
    {
        $em = $this->getDoctrine()->getManager();
        $brand = $em->getRepository(PhoneBrand::class)->findOneBy(["url" => $urlBrand]);

        if(!($brand instanceof PhoneBrand)){
            return $this->redirect($request->headers->get('referer'));
        }

        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $allModels = $em->getRepository(PhoneModel::class)->findBy(
            [
                "active" => true,
                "brand" => $brand,
            ],
            ["name" => "ASC"]);

        $popularModels = array_filter($allModels, function(PhoneModel $model){
            return $model->isPopular();
        });

        $parameters = [$brand];

        /** @var CatalogPhoneSparePartChoicePhoneModel $page */
        $page = $em->getRepository(CatalogPhoneSparePartChoicePhoneModel::class)->findAll()[0];

        $page->setReturnButtonText($transformer->transformPage($page->getReturnButtonText(), $parameters));
        $page->setReturnButtonLink($transformer->transformPage($page->getReturnButtonLink(), $parameters));

        return $this->render('client/phone/catalog/spare-part/choice-model.html.twig', [
            'allModels' => $allModels,
            'popularModels' => $popularModels,
            'page' => $transformer->transformPage($page, [$brand]),
            'brand' => $brand,
        ]);
    }

    /**
     * @Route("/{urlBrand}/{urlModel}/", name="show_phone_spare_part_catalog_choice_phone_spare_part")
     */
    public function showCatalogChoiceSparePartAction(Request $request, $urlBrand, $urlModel, VariableTransformer $transformer)
    {
        $em = $this->getDoctrine()->getManager();
        $brand = $em->getRepository(PhoneBrand::class)->findOneBy(["url" => $urlBrand]);
        $model = $em->getRepository(PhoneModel::class)->findOneBy(["url" => $urlModel]);

        if(!($brand instanceof PhoneBrand) || !($model instanceof PhoneModel)){
            return $this->redirect($request->headers->get('referer'));
        }

        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $allSpareParts = $em->getRepository(PhoneSparePart::class)->findBy(["active" => true], ["name" => "ASC"]);

        $popularSpareParts = array_filter($allSpareParts, function(PhoneSparePart $sparePart){
            return $sparePart->isPopular();
        });

        $page = $em->getRepository(CatalogPhoneSparePartChoicePhoneSparePart::class)->findAll()[0];

        return $this->render('client/phone/catalog/spare-part/choice-spare-part.html.twig', [
            'allSpareParts' => $allSpareParts,
            'popularSpareParts' => $popularSpareParts,
            'page' => $transformer->transformPage($page, [$brand, $model]),
            'brand' => $brand,
        ]);
    }

    /**
     * @Route("/{urlBrand}/{urlModel}/{urlSP}/", name="show_phone_spare_part_catalog_choice_city")
     */
    public function showChoiceCityAction(Request $request, $urlBrand, $urlModel, $urlSP, VariableTransformer $transformer)
    {
        $em = $this->getDoctrine()->getManager();
        $sparePart = $em->getRepository(PhoneSparePart::class)->findOneBy(["url" => $urlSP]);
        $brand = $em->getRepository(PhoneBrand::class)->findOneBy(["url" => $urlBrand]);
        $model = $em->getRepository(PhoneModel::class)->findOneBy(["url" => $urlModel]);

        if(!($sparePart instanceof PhoneSparePart) || !($brand instanceof PhoneBrand) || !($model instanceof PhoneModel)){
            return $this->redirect($request->headers->get('referer'));
        }

        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $capitals = $em->getRepository(City::class)->findBy(
            ["type" => City::CAPITAL_TYPE],
            ["name" => "ASC"]
        );

        $othersCities = $em->getRepository(City::class)->findBy(
            [
                "type" => [City::REGIONAL_CITY_TYPE, City::OTHERS_TYPE],
                "active" => true,
            ],
            ["name" => "ASC"]
        );

        $page = $em->getRepository(CatalogPhoneSparePartChoiceCity::class)->findAll()[0];

        return $this->render('client/phone/catalog/spare-part/choice-city.html.twig', [
            'capitals' => $capitals,
            'otherCities' => $othersCities,
            'page' => $transformer->transformPage($page, [$sparePart, $brand, $model]),
        ]);
    }

    /**
     * @Route("/{urlBrand}/{urlModel}/{urlSP}/{urlCity}/", name="show_phone_spare_part_catalog_final_page")
     */
    public function showCatalogFinalPageAction(Request $request, $urlBrand, $urlModel, $urlSP, $urlCity, VariableTransformer $transformer)
    {
        $em = $this->getDoctrine()->getManager();
        $sparePart = $em->getRepository(PhoneSparePart::class)->findOneBy(["url" => $urlSP]);
        $brand = $em->getRepository(PhoneBrand::class)->findOneBy(["url" => $urlBrand]);
        $model = $em->getRepository(PhoneModel::class)->findOneBy(["url" => $urlModel]);
        $city = $em->getRepository(City::class)->findOneBy(["url" => $urlCity]);

        if(!($sparePart instanceof PhoneSparePart) || !($brand instanceof PhoneBrand) ||
            !($model instanceof PhoneModel) || !($city instanceof City)){
            return $this->redirect($request->headers->get('referer'));
        }

        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $page = $em->getRepository(CatalogPhoneSparePartFinalPage::class)->findAll()[0];
        $parameters = [$sparePart, $brand, $model, $city];

        /** @var CatalogPhoneSparePartFinalPage $transformedPage */
        $transformedPage = $transformer->transformPage($page, $parameters);
        $transformedPage->setReturnButtonText($transformer->transformPage($transformedPage->getReturnButtonText(), $parameters));
        $transformedPage->setReturnButtonLink($transformer->transformPage($transformedPage->getReturnButtonLink(), $parameters));

        return $this->render('client/phone/catalog/spare-part/final-page.html.twig', [
            'page' => $transformedPage,
        ]);
    }
}