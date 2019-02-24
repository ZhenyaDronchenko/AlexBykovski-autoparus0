<?php

namespace App\Controller\UserOffice\ProductCategories;

use App\Entity\Advert\AutoSparePart\AutoSparePartGeneralAdvert;
use App\Entity\Advert\AutoSparePart\AutoSparePartSpecificAdvert;
use App\Entity\Brand;
use App\Entity\Client\Client;
use App\Entity\Model;
use App\Entity\SparePart;
use App\Entity\UserData\UserEngine;
use App\Form\Type\SparePartGeneralAdvertType;
use App\Form\Type\SparePartSpecificAdvertType;
use App\Provider\SellerOffice\SpecificAdvertListProvider;
use App\Type\AutoSparePartSpecificAdvertFilterType;
use App\Upload\FileUpload;
use DateTime;
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
    public function showListAdvertsAction(Request $request, SpecificAdvertListProvider $provider)
    {
        /** @var Client $client */
        $client = $this->getUser();
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $generalAdverts = $em->getRepository(AutoSparePartGeneralAdvert::class)->findBy([
                "sellerAdvertDetail" => $client->getSellerData()->getAdvertDetail()
            ],
            [
                "updatedAt" => "DESC"
            ]
        );

        return $this->render('client/user-office/seller-services/product-categories/spare-part/list-adverts.html.twig', [
            "generalAdverts" => $generalAdverts,
            "specificAdvertBrands" => $provider->getBrands($client),
            "specificAdvertSpareParts" => $provider->getSpareParts($client),
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
        $isBrandSubmitted = false;
        $isSimpleSparePartSubmitted = false;
        $isAjax = !is_null($request->query->get("ajax"));
        $redirectToUrl = null;

        $form = $this->createForm(SparePartGeneralAdvertType::class, $advert);

        $form->handleRequest($request);

        if($form->isSubmitted()){
            $isBrandSubmitted = $form->get("submitGeneral")->isClicked();
            $isSimpleSparePartSubmitted = $form->get("submitSparePart")->isClicked();
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
                $isNewElement = false;

                if(!$advert->getId()){
                    $em->persist($advert);
                    $em->flush();
                    $em->refresh($advert);

                    $isNewElement = true;
                }

                $em->flush();

                $redirectFirstUrl = $isNewElement ?
                    $this->generateUrl("user_profile_product_categories_spare_part_add_general_advert") :
                    $this->generateUrl("user_profile_product_categories_spare_part_edit_general_advert", ["id" => $advert->getId()]);

                $redirectToUrl = $isSimpleSparePartSubmitted ?
                    $redirectFirstUrl :
                    $this->generateUrl("user_profile_product_categories_spare_part_list_adverts", ["tab" => "general"]);
            }
        }

        if($isAjax){
            return $this->render('client/user-office/seller-services/product-categories/spare-part/forms/add-general-advert-form.html.twig', [
                "form" => $form->createView(),
                "isConfirmBrand" => $isValid && $isBrandSubmitted,
                "isValid" => $isValid,
                "advertId" => $advert->getId(),
                "redirectToUrl" => $redirectToUrl,
            ]);
        }
        else{
            return $this->render('client/user-office/seller-services/product-categories/spare-part/add-general-advert.html.twig', [
                "form" => $form->createView(),
                "isConfirmBrand" => $isValid && $isBrandSubmitted,
                "isValid" => $isValid,
                "advertId" => $advert->getId(),
                "redirectToUrl" => $redirectToUrl,
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
        /** @var FileUpload $uploader */
        $uploader = $this->get("app.file_upload");
        $uploader->setFolder(FileUpload::AUTO_SPARE_PART_SPECIFIC_ADVERT);

        if(!($advert instanceof AutoSparePartSpecificAdvert)){
            $parentAutoAdvertId = $request->query->get("parent_auto", 0);
            $parentSparePartAdvertId = $request->query->get("parent_spare_part", 0);

            if($parentAutoAdvertId > 0) {
                $parentAdvert = $em->getRepository(AutoSparePartSpecificAdvert::class)->find($parentAutoAdvertId);
            }
            else{
                $parentAdvert = $em->getRepository(AutoSparePartSpecificAdvert::class)->find($parentSparePartAdvertId);
            }

            if($parentAdvert instanceof AutoSparePartSpecificAdvert &&
                $client->getSellerData()->getAdvertDetail()->getId() === $parentAdvert->getSellerAdvertDetail()->getId()){
                $advert = $parentAutoAdvertId > 0 ? $parentAdvert->createCloneByAuto() : $parentAdvert->createCloneBySparePart();
            }
        }

        $advert = $advert ?: new AutoSparePartSpecificAdvert($client->getSellerData()->getAdvertDetail());
        $isFormSubmitted = array_key_exists("spare_part_specific_advert", $_POST);

        $form = $this->createForm(SparePartSpecificAdvertType::class, $advert, ["isFormSubmitted" => $isFormSubmitted]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $fileData = $form->get("image")->getData();
            $newEmptyName = $request->request->get("engineNameEmpty");

            if($newEmptyName){
                $advert->setEngineName(strtoupper($newEmptyName));
            }

            if($newEmptyName || $advert->getEngineType() &&
                !count($advert->getModel()->getEngineCapacities($advert->getEngineType())) && $advert->getEngineCapacity()){
                $userEngine = UserEngine::createByAutoSparePartSpecificAdvert($advert);

                $em->persist($userEngine);
            }

            if($fileData){
                $path = $uploader->uploadBase64Image($fileData);
                $advert->setImage($path);
            }

            $em->persist($advert);
            $em->flush();

            switch($form->get('submitButtonName')->getData()){
                case "submitAdd":
                    $redirectUrl = $this->generateUrl("user_profile_product_categories_spare_part_add_specific_advert");

                    break;
                case "submitAutoContinue":
                    $redirectUrl = $this->generateUrl("user_profile_product_categories_spare_part_add_specific_advert", ["parent_auto" => $advert->getId()]);

                    break;
                case "submitSparePartContinue":
                    $redirectUrl = $this->generateUrl("user_profile_product_categories_spare_part_add_specific_advert", ["parent_spare_part" => $advert->getId()]);

                    break;
                default:
                    $redirectUrl = $this->generateUrl("user_profile_product_categories_spare_part_list_adverts");

                    break;
            }

            return $this->render('client/user-office/seller-services/product-categories/spare-part/forms/add-specific-advert-form.html.twig', [
                "form" => $form->createView(),
                "redirectUrl" => $redirectUrl,
                "advert" => $advert,
            ]);
        }
        elseif ($form->isSubmitted() && !$form->isValid()){
            $newEngineName = $request->request->get("engineNameEmpty");
            $form = $this->createForm(SparePartSpecificAdvertType::class, $advert, ["isFormSubmitted" => false]);
            $form->handleRequest($request);

            return $this->render('client/user-office/seller-services/product-categories/spare-part/forms/add-specific-advert-form.html.twig', [
                "form" => $form->createView(),
                "advert" => $advert,
                "engineNameEmpty" => $newEngineName
            ]);
        }

        $form = $this->createForm(SparePartSpecificAdvertType::class, $advert, ["isFormSubmitted" => false]);

        return $this->render('client/user-office/seller-services/product-categories/spare-part/add-specific-advert.html.twig', [
            "form" => $form->createView(),
            "advert" => $advert,
        ]);
    }

    /**
     * @Route("/delete-specific-advert/{id}", name="user_profile_product_categories_spare_part_delete_specific_advert")
     *
     * @ParamConverter("advert", class="App\Entity\Advert\AutoSparePart\AutoSparePartSpecificAdvert", options={"id" = "id"})
     */
    public function deleteSpecificAdvertAction(Request $request, AutoSparePartSpecificAdvert $advert)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $em->remove($advert);
        $em->flush();

        return $this->redirectToRoute("user_profile_product_categories_spare_part_list_adverts");
    }

    /**
     * @Route("/ajax/models-by-brand/{id}", name="user_profile_product_categories_spare_part_ajax_get_models_by_brand")
     *
     * @ParamConverter("brand", class="App\Entity\Brand", options={"id" = "id"})
     */
    public function ajaxGetModelsByBrandAction(Request $request, Brand $brand, SpecificAdvertListProvider $provider)
    {
        return new JsonResponse($provider->getModels($this->getUser(), $brand));
    }

    /**
     * @Route("/ajax/change-specific-advert-activity/{id}", name="user_profile_product_categories_spare_part_ajax_change_specific_advert_activity")
     *
     * @ParamConverter("advert", class="App\Entity\Advert\AutoSparePart\AutoSparePartSpecificAdvert", options={"id" = "id"})
     */
    public function ajaxChangeActivitySpecificAdvertAction(Request $request, AutoSparePartSpecificAdvert $advert)
    {
        $advert->setIsActive(!$advert->isActive());

        if($advert->isActive()){
            $advert->setActivatedAt(new DateTime());
        }

        $this->getDoctrine()->getManager()->flush();

        $advertArray = $advert->toArray();

        return new JsonResponse([
            "isActive" => $advertArray["isActive"],
            "date" => $advertArray["activatedAt"],
        ]);
    }

    /**
     * @Route("/ajax/specific-adverts-by-parameters", name="user_profile_product_categories_spare_part_ajax_get_specific_adverts_by_parameters")
     */
    public function ajaxGetSpecificAdvertsByParametersAction(Request $request, SpecificAdvertListProvider $provider)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        /** @var Client $client */
        $client = $this->getUser();

        $requestData = json_decode($request->getContent(), true);
        /** @var Brand|null $brand */
        $brand = $em->getRepository(Brand::class)->find($requestData["brand"] ?: "");
        /** @var Model|null $model */
        $model = $em->getRepository(Model::class)->find($requestData["model"] ?: "");
        /** @var SparePart|null $sparePart */
        $sparePart = $em->getRepository(SparePart::class)->find($requestData["sparePart"] ?: "");
        $page = (int)$requestData["page"];

        $filterType = new AutoSparePartSpecificAdvertFilterType($client, $brand, $model, $sparePart, $page);

        return new JsonResponse([
            "adverts" => $provider->getAdverts($filterType),
            "countPages" => $provider->getCountPagesAdverts($filterType),
        ]);
    }
}