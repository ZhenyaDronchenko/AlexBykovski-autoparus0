<?php

namespace App\Controller\UserOffice\ProductCategories;

use App\Entity\Advert\AutoSparePart\AutoSparePartGeneralAdvert;
use App\Entity\Advert\AutoSparePart\AutoSparePartSpecificAdvert;
use App\Entity\Brand;
use App\Entity\Client\Client;
use App\Form\Type\SparePartGeneralAdvertType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/user-office/product-categories/spare-part")
 *
 * @Security("has_role('ROLE_CLIENT')")
 */
class SparePartCategoryController extends Controller
{
    /**
     * @Route("/", name="user_profile_product_categories_spare_part")
     */
    public function showProductCategoriesSparePartAction(Request $request)
    {
        return $this->render('client/user-office/seller-services/product-categories/spare-part-category.html.twig', []);
    }

    /**
     * @Route("/list-adverts", name="user_profile_product_categories_spare_part_list_adverts")
     */
    public function showListAdvertsAction(Request $request)
    {
        /** @var Client $client */
        $client = $this->getUser();
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $generalAdverts = $em->getRepository(AutoSparePartGeneralAdvert::class)->findBy([
            "sellerAdvertDetail" => $client->getSellerData()->getAdvertDetail()
        ]);

        return $this->render('client/user-office/seller-services/product-categories/spare-part/list-adverts.html.twig', [
            "generalAdverts" => $generalAdverts,
        ]);
    }

    /**
     * @Route("/add-general-advert", name="user_profile_product_categories_spare_part_add_general_advert")
     * @Route("/add-general-advert/{id}", name="user_profile_product_categories_spare_part_add_second_part_general_advert", requirements={"id"="\d+"})
     * @Route("/edit-general-advert/{id}", name="user_profile_product_categories_spare_part_edit_general_advert")
     *
     * @ParamConverter("advert", class="App\Entity\Advert\AutoSparePart\AutoSparePartGeneralAdvert", options={"id" = "id"})
     */
    public function addGeneralAdvertAction(Request $request, AutoSparePartGeneralAdvert $advert = null)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        /** @var Client $client */
        $client = $this->getUser();
        $advert = $advert ?: new AutoSparePartGeneralAdvert($client->getSellerData()->getAdvertDetail());
        $isValid = false;
        $isBrandSubmitted = !is_null($request->query->get("brandSubmit"));
        $isAjax = !is_null($request->query->get("ajax"));

        $form = $this->createForm(SparePartGeneralAdvertType::class, $advert);

        $form->handleRequest($request);

        if($form->isSubmitted()){
            $brand = $form->get("brand")->getData();

            $form = $this->createForm(SparePartGeneralAdvertType::class, $advert, ["brand" => $brand]);
            $form->handleRequest($request);
        }

        if($form->isSubmitted() && $form->isValid()){
            if(!$advert->getCondition()){
                $form->get("condition")->addError(new FormError("Выберите вариант"));
            }
            if(!$advert->getStockType()){
                $form->get("stockType")->addError(new FormError("Выберите вариант"));
            }

            if($advert->getBrand() instanceof Brand && $advert->getBrand()->getId() < 0){
                $form->get("brand")->addError(new FormError("Сделайте выбор"));
            }
            elseif($advert->getBrand() instanceof Brand && !$advert->getModels()->count() && $advert->getBrand()->getModels()->count()){
                $form->get("models")->addError(new FormError("Выберите хотя бы 1 модель"));
            }

            if(!$isBrandSubmitted && !$advert->getSpareParts()->count()){
                $form->get("spareParts")->addError(new FormError("Выберите хотя бы 1 запчасть"));
            }

            if($form->getErrors(true, false)->count() === 0){
                $isValid = true;

                if(!$advert->getId()){
                    $em->persist($advert);
                    $em->flush();
                    $em->refresh($advert);
                }

                $em->flush();
            }
        }

        if($isAjax){
            return $this->render('client/user-office/seller-services/product-categories/spare-part/forms/add-general-advert-form.html.twig', [
                "form" => $form->createView(),
                "isConfirmBrand" => $isValid && $isBrandSubmitted,
                "isValid" => $isValid,
                "advertId" => $advert->getId(),
            ]);
        }
        else{
            return $this->render('client/user-office/seller-services/product-categories/spare-part/add-general-advert.html.twig', [
                "form" => $form->createView(),
                "isConfirmBrand" => $isValid && $isBrandSubmitted,
                "isValid" => $isValid,
                "advertId" => $advert->getId(),
            ]);
        }
    }

    /**
     * @Route("/delete-general-advert/{id}", name="user_profile_product_categories_spare_part_delete_general_advert")
     *
     * @ParamConverter("advert", class="App\Entity\Advert\AutoSparePart\AutoSparePartGeneralAdvert", options={"id" = "id"})
     */
    public function deleteGeneralAdvertAction(Request $request, AutoSparePartGeneralAdvert $advert)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $em->remove($advert);
        $em->flush();

        return $this->redirectToRoute("user_profile_product_categories_spare_part_list_adverts");
    }

    /**
     * @Route("/add-specific-advert", name="user_profile_product_categories_spare_part_add_specific_advert")
     * @Route("/edit-specific-advert/{id}", name="user_profile_product_categories_spare_part_edit_specific_advert")
     *
     * @ParamConverter("advert", class="App\Entity\Advert\AutoSparePart\AutoSparePartSpecificAdvert", options={"id" = "id"})
     */
    public function addSpecificAdvertAction(Request $request, AutoSparePartSpecificAdvert $advert = null)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        /** @var Client $client */
        $client = $this->getUser();

        return $this->render('client/user-office/seller-services/product-categories/spare-part/add-specific-advert.html.twig', [
//                "form" => $form->createView(),
//                "isConfirmBrand" => $isValid && $isBrandSubmitted,
//                "isValid" => $isValid,
//                "advertId" => $advert->getId(),
            ]);


//        $advert = $advert ?: new AutoSparePartGeneralAdvert($client->getSellerData()->getAdvertDetail());
//        $isValid = false;
//        $isBrandSubmitted = !is_null($request->query->get("brandSubmit"));
//        $isAjax = !is_null($request->query->get("ajax"));
//
//        $form = $this->createForm(SparePartGeneralAdvertType::class, $advert);
//
//        $form->handleRequest($request);
//
//        if($form->isSubmitted()){
//            $brand = $form->get("brand")->getData();
//
//            $form = $this->createForm(SparePartGeneralAdvertType::class, $advert, ["brand" => $brand]);
//            $form->handleRequest($request);
//        }
//
//        if($form->isSubmitted() && $form->isValid()){
//            if(!$advert->getCondition()){
//                $form->get("condition")->addError(new FormError("Выберите вариант"));
//            }
//            if(!$advert->getStockType()){
//                $form->get("stockType")->addError(new FormError("Выберите вариант"));
//            }
//
//            if($advert->getBrand() instanceof Brand && $advert->getBrand()->getId() < 0){
//                $form->get("brand")->addError(new FormError("Сделайте выбор"));
//            }
//            elseif($advert->getBrand() instanceof Brand && !$advert->getModels()->count() && $advert->getBrand()->getModels()->count()){
//                $form->get("models")->addError(new FormError("Выберите хотя бы 1 модель"));
//            }
//
//            if(!$isBrandSubmitted && !$advert->getSpareParts()->count()){
//                $form->get("spareParts")->addError(new FormError("Выберите хотя бы 1 запчасть"));
//            }
//
//            if($form->getErrors(true, false)->count() === 0){
//                $isValid = true;
//
//                if(!$advert->getId()){
//                    $em->persist($advert);
//                    $em->flush();
//                    $em->refresh($advert);
//                }
//
//                $em->flush();
//            }
//        }
//
//        if($isAjax){
//            return $this->render('client/user-office/seller-services/product-categories/spare-part/forms/add-general-advert-form.html.twig', [
//                "form" => $form->createView(),
//                "isConfirmBrand" => $isValid && $isBrandSubmitted,
//                "isValid" => $isValid,
//                "advertId" => $advert->getId(),
//            ]);
//        }
//        else{
//            return $this->render('client/user-office/seller-services/product-categories/spare-part/add-general-advert.html.twig', [
//                "form" => $form->createView(),
//                "isConfirmBrand" => $isValid && $isBrandSubmitted,
//                "isValid" => $isValid,
//                "advertId" => $advert->getId(),
//            ]);
//        }
    }
}