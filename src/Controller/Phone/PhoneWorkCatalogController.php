<?php

namespace App\Controller\Phone;

use App\Entity\City;
use App\Entity\General\NotFoundPage;
use App\Entity\Phone\Catalog\Work\CatalogPhoneWorkChoiceCity;
use App\Entity\Phone\Catalog\Work\CatalogPhoneWorkChoicePhoneBrand;
use App\Entity\Phone\Catalog\Work\CatalogPhoneWorkChoicePhoneModel;
use App\Entity\Phone\Catalog\Work\CatalogPhoneWorkChoicePhoneWork;
use App\Entity\Phone\Catalog\Work\CatalogPhoneWorkFinalPage;
use App\Entity\Phone\PhoneBrand;
use App\Entity\Phone\PhoneModel;
use App\Entity\Phone\PhoneSparePart;
use App\Transformer\VariableTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/phon_remont")
 */
class PhoneWorkCatalogController extends Controller
{
    /**
     * @Route("/", name="show_phone_work_catalog_choice_work")
     */
    public function showCatalogChoiceWorkAction(Request $request)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $allSpareParts = $em->getRepository(PhoneSparePart::class)->findBy(["active" => true], ["work" => "ASC"]);

        $popularSpareParts = array_filter($allSpareParts, function(PhoneSparePart $sparePart){
            return $sparePart->isPopular();
        });

        return $this->render('client/phone/catalog/work/choice-work.html.twig', [
            'allSpareParts' => $allSpareParts,
            'popularSpareParts' => $popularSpareParts,
            'page' => $em->getRepository(CatalogPhoneWorkChoicePhoneWork::class)->findAll()[0]
        ]);
    }

    /**
     * @Route("/{url}/", name="show_phone_work_catalog_choice_phone_brand")
     */
    public function showCatalogChoiceBrandAction(Request $request, $url, VariableTransformer $transformer)
    {
        $em = $this->getDoctrine()->getManager();
        $sparePart = $em->getRepository(PhoneSparePart::class)->findOneBy(["url" => $url]);

        if(!($sparePart instanceof PhoneSparePart)){
            throw new NotFoundHttpException(NotFoundPage::DEFAULT_MESSAGE);
        }

        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $allBrands = $em->getRepository(PhoneBrand::class)->findBy(["active" => true], ["name" => "ASC"]);

        $popularBrands = array_filter($allBrands, function(PhoneBrand $brand){
            return $brand->isPopular();
        });

        $page = $em->getRepository(CatalogPhoneWorkChoicePhoneBrand::class)->findAll()[0];

        return $this->render('client/phone/catalog/work/choice-brand.html.twig', [
            'allBrands' => $allBrands,
            'popularBrands' => $popularBrands,
            'page' => $transformer->transformPage($page, [$sparePart]),
            'sparePart' => $sparePart,
        ]);
    }

    /**
     * @Route("/{urlSP}/{urlBrand}/", name="show_phone_work_catalog_choice_phone_model")
     */
    public function showCatalogChoiceModelAction(Request $request, $urlSP, $urlBrand, VariableTransformer $transformer)
    {
        $em = $this->getDoctrine()->getManager();
        $sparePart = $em->getRepository(PhoneSparePart::class)->findOneBy(["url" => $urlSP]);
        $brand = $em->getRepository(PhoneBrand::class)->findOneBy(["url" => $urlBrand]);

        if(!($sparePart instanceof PhoneSparePart) || !($brand instanceof PhoneBrand)){
            throw new NotFoundHttpException(NotFoundPage::DEFAULT_MESSAGE);
        }

        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $allModels = $em->getRepository(PhoneModel::class)->findBy([
            "active" => true,
            "brand" => $brand,
        ],
            ["name" => "ASC"]);

        $popularModels = array_filter($allModels, function(PhoneModel $model){
            return $model->isPopular();
        });

        $page = $em->getRepository(CatalogPhoneWorkChoicePhoneModel::class)->findAll()[0];

        return $this->render('client/phone/catalog/work/choice-model.html.twig', [
            'allModels' => $allModels,
            'popularModels' => $popularModels,
            'page' => $transformer->transformPage($page, [$sparePart, $brand]),
            'brand' => $brand,
            'sparePart' => $sparePart,
        ]);
    }

    /**
     * @Route("/{urlSP}/{urlBrand}/{urlModel}/", name="show_phone_work_catalog_choice_city")
     */
    public function showCatalogChoiceCityAction(Request $request, $urlSP, $urlBrand, $urlModel, VariableTransformer $transformer)
    {
        $em = $this->getDoctrine()->getManager();
        $sparePart = $em->getRepository(PhoneSparePart::class)->findOneBy(["url" => $urlSP]);
        $brand = $em->getRepository(PhoneBrand::class)->findOneBy(["url" => $urlBrand]);
        $model = $em->getRepository(PhoneModel::class)->findOneBy(["url" => $urlModel]);

        if(!($sparePart instanceof PhoneSparePart) || !($brand instanceof PhoneBrand) || !($model instanceof PhoneModel)){
            throw new NotFoundHttpException(NotFoundPage::DEFAULT_MESSAGE);
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

        $page = $em->getRepository(CatalogPhoneWorkChoiceCity::class)->findAll()[0];

        return $this->render('client/phone/catalog/work/choice-city.html.twig', [
            'capitals' => $capitals,
            'otherCities' => $othersCities,
            'page' => $transformer->transformPage($page, [$sparePart, $brand, $model]),
        ]);
    }

    /**
     * @Route("/{urlSP}/{urlBrand}/{urlModel}/{urlCity}", name="show_phone_work_catalog_final_page")
     */
    public function showCatalogFinalPageAction(Request $request, $urlSP, $urlBrand, $urlModel, $urlCity, VariableTransformer $transformer)
    {
        $em = $this->getDoctrine()->getManager();
        $sparePart = $em->getRepository(PhoneSparePart::class)->findOneBy(["url" => $urlSP]);
        $brand = $em->getRepository(PhoneBrand::class)->findOneBy(["url" => $urlBrand]);
        $model = $em->getRepository(PhoneModel::class)->findOneBy(["url" => $urlModel]);
        $city = $em->getRepository(City::class)->findOneBy(["url" => $urlCity]);

        if(!($sparePart instanceof PhoneSparePart) || !($brand instanceof PhoneBrand) ||
            !($model instanceof PhoneModel) || !($city instanceof City)){
            throw new NotFoundHttpException(NotFoundPage::DEFAULT_MESSAGE);
        }

        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $page = $em->getRepository(CatalogPhoneWorkFinalPage::class)->findAll()[0];
        $parameters = [$sparePart, $brand, $model, $city];

        /** @var CatalogPhoneWorkFinalPage $transformedPage */
        $transformedPage = $transformer->transformPage($page, $parameters);
        $transformedPage->setReturnButtonText($transformer->transformPage($transformedPage->getReturnButtonText(), $parameters));
        $transformedPage->setReturnButtonLink($transformer->transformPage($transformedPage->getReturnButtonLink(), $parameters));

        return $this->render('client/phone/catalog/work/final-page.html.twig', [
            'page' => $transformedPage,
        ]);
    }
}