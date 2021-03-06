<?php

namespace App\Controller\UserOffice;

use App\Entity\Brand;
use App\Entity\Client\Client;
use App\Entity\Client\SellerAdvertDetail;
use App\Entity\Client\SellerCompany;
use App\Entity\Client\SellerCompanyWorkflow;
use App\Entity\Client\SellerData;
use App\Entity\Client\UserCar;
use App\Entity\EngineType;
use App\Entity\Image;
use App\Entity\Model;
use App\Form\Type\ClientCarsType;
use App\Form\Type\PersonalDataType;
use App\Form\Type\SellerCompanyType;
use App\Provider\Form\ClientCarProvider;
use App\Provider\Form\SparePartAdvertDataProvider;
use App\Provider\GeoLocation\GeoLocationProvider;
use App\Upload\Client\UserOfficeUploader;
use App\Upload\FileUpload;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Gumlet\ImageResizeException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/user-office")
 *
 * @Security("has_role('ROLE_CLIENT')")
 */
class UserOfficeController extends Controller
{
    /**
     * @Route("/", name="show_user_office")
     */
    public function editUserOfficeAction(Request $request)
    {
        return $this->render('client/user-office/user-office.html.twig', []);
    }

    /**
     * @Route("/base-profile", name="show_user_base_profile")
     */
    public function editUserBaseProfileAction(Request $request)
    {
        /** @var Client $client */
        $client = $this->getUser();

        $formPersonalData = $this->createForm(PersonalDataType::class, $client);
        $formCars = $this->createForm(ClientCarsType::class, $client, ["isFormSubmitted" => false]);

        return $this->render('client/user-office/user-base-profile.html.twig', [
            "formPersonalData" => $formPersonalData->createView(),
            "formCars" => $formCars->createView(),
        ]);
    }

    /**
     * @Route("/edit-personal-data", name="edit_user_personal_data")
     */
    public function editPersonalDataAction(Request $request)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        /** @var Client $client */
        $client = $this->getUser();

        $form = $this->createForm(PersonalDataType::class, $client);

        $form->handleRequest($request);

        $isValid = false;

        if($form->isSubmitted() && $form->isValid()){
            $client->setPhone($client->getPhone());
            $isValid = true;

            $em->flush();
        }

        return $this->render('client/user-office/edit-profile-form/personal-data.html.twig', [
            "form" => $form->createView(),
            "isValid" => $isValid
        ]);
    }

    /**
     * @Route("/edit-cars", name="edit_user_cars")
     */
    public function editCarsAction(Request $request)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        /** @var Client $client */
        $client = $this->getUser();
        $isValid = false;
        $isFormSubmitted = array_key_exists("client_cars", $_POST);
        $originalCars = new ArrayCollection();

        foreach ($client->getCars() as $car) {
            $originalCars->add($car);
        }

        $form = $this->createForm(ClientCarsType::class, $client, ["isFormSubmitted" => $isFormSubmitted]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $isValid = true;

            foreach ($originalCars as $car) {
                if (false === $client->getCars()->contains($car)) {
                    $em->remove($car);
                }
            }

            /** @var UserCar $car */
            foreach ($client->getCars() as $car) {
                $car->setClient($client);
            }

            $em->flush();

            $em->refresh($client);
        }

        $form = $this->createForm(ClientCarsType::class, $client, ["isFormSubmitted" => false]);

        return $this->render('client/user-office/edit-profile-form/cars.html.twig', [
            "form" => $form->createView(),
            "isValid" => $isValid
        ]);
    }

    /**
     * @Route("/business-profile", name="show_user_business_office")
     */
    public function editBusinessProfileAction(Request $request)
    {
        /** @var Client $client */
        $client = $this->getUser();

        if($client->isSeller()){
            $sellerCompany = $client->getSellerData()->getSellerCompany();
        }
        else{
            $newSellerCompanyWorkflow = new SellerCompanyWorkflow();
            $sellerCompany = new SellerCompany();
            $sellerCompany->setWorkflow($newSellerCompanyWorkflow);
        }

        $form = $this->createForm(SellerCompanyType::class, $sellerCompany);

        return $this->render('client/user-office/user-business-profile.html.twig', [
            "form" => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit-business-data", name="edit_user_business_data")
     */
    public function editBusinessDataAction(Request $request)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        /** @var Client $client */
        $client = $this->getUser();
        $isValid = false;

        if($client->isSeller()){
            $sellerCompany = $client->getSellerData()->getSellerCompany();
        }
        else{
            $newSellerCompanyWorkflow = new SellerCompanyWorkflow();
            $sellerCompany = new SellerCompany();
            $sellerCompany->setWorkflow($newSellerCompanyWorkflow);
        }

        $form = $this->createForm(SellerCompanyType::class, $sellerCompany);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            if(!$client->isSeller()){
                $sellerData = new SellerData($client);
                $sellerData->setClient($client);
                $sellerData->setSellerCompany($sellerCompany);
                $client->setSellerData($sellerData);

                $sellerAdvertDetail = new SellerAdvertDetail();
                $sellerAdvertDetail->setSellerData($sellerData);
                $sellerData->setAdvertDetail($sellerAdvertDetail);

                $em->persist($sellerAdvertDetail);
                $em->persist($sellerCompany);
                $em->persist($sellerData);
                $em->persist($sellerCompany->getWorkflow());
            }

            $em->flush();

            $isValid = true;
        }

        return $this->render('client/user-office/edit-profile-form/business-data.html.twig', [
            "form" => $form->createView(),
            "isValid" => $isValid,
        ]);
    }

    /**
     * @Route("/product-categories", name="user_profile_product_categories")
     *
     * @Security("has_role('ROLE_CLIENT')")
     */
    public function showProductCategoriesAction(Request $request)
    {
        /** @var SellerCompany $sellerCompany */
        $sellerCompany = $this->getUser()->getSellerData()->getSellerCompany();

        if($sellerCompany->isSparePartSeller() && !$sellerCompany->isAutoSeller()){
            return $this->redirectToRoute("user_profile_product_categories_spare_part");
        }

        return $this->render('client/user-office/seller-services/product-categories.html.twig', []);
    }

    /**
     * @Route("/import-specific-adverts", name="user_office_import_specific_adverts")
     */
    public function showImportSpecificAdvertsAction(Request $request)
    {
        return $this->render('client/user-office/seller-services/product-categories/spare-part/import-specific-adverts.html.twig', []);
    }

    /**
     * @Route("/get-models-by-brand", name="get_models_by_brand")
     */
    public function getModelsByBrandAction(Request $request, ClientCarProvider $provider){
        $brandId = $request->query->get("brand");

        $brand = $this->getDoctrine()->getRepository(Brand::class)->find($brandId);

        return new JsonResponse($provider->getModels($brand));
    }

    /**
     * @Route("/get-years-by-model", name="get_years_by_model")
     */
    public function getYearsByModelAction(Request $request, ClientCarProvider $provider){
        $modelId = $request->query->get("model");

        $model = $this->getDoctrine()->getRepository(Model::class)->find($modelId);

        return new JsonResponse($provider->getYears($model));
    }

    /**
     * @Route("/get-vehicles-by-model", name="get_vehicles_by_model")
     */
    public function getVehiclesByModelAction(Request $request, ClientCarProvider $provider){
        $modelId = $request->query->get("model");

        $model = $this->getDoctrine()->getRepository(Model::class)->find($modelId);

        return new JsonResponse($provider->getVehicleTypes($model));
    }

    /**
     * @Route("/get-engine-types-by-model", name="get_engine_types_by_model")
     */
    public function getEngineTypesByModelAction(Request $request, ClientCarProvider $provider){
        $modelId = $request->query->get("model");

        $model = $this->getDoctrine()->getRepository(Model::class)->find($modelId);

        return new JsonResponse($provider->getEngineTypes($model));
    }

    /**
     * @Route("/get-capacities-by-model-engine-type", name="get_capacities_by_model_engine_type")
     */
    public function getCapacitiesByModelEngineTypeAction(Request $request, ClientCarProvider $provider)
    {
        $modelId = $request->query->get("model");
        $engineTypeId = $request->query->get("engine_type");

        $model = $this->getDoctrine()->getRepository(Model::class)->find($modelId);
        $engineType = $this->getDoctrine()->getRepository(EngineType::class)->find($engineTypeId);

        return new JsonResponse($provider->getCapacities($model, $engineType));
    }

    /**
     * @Route("/get-car-data-by-model", name="get_car_data_by_model")
     */
    public function getCarDataByModelAction(Request $request, SparePartAdvertDataProvider $provider)
    {
        $modelId = $request->query->get("model");

        $model = $this->getDoctrine()->getRepository(Model::class)->find($modelId);

        $data = [
            "years" => $provider->getYears($model),
            "engineTypes" => $provider->getEngineTypes($model),
            "gearBoxTypes" => $provider->getGearBoxTypes($model),
            "vehicleTypes" => $provider->getVehicleTypes($model),
            "driveTypes" => $provider->getDriveTypes($model),
        ];

        return new JsonResponse($data);
    }

    /**
     * @Route("/get-car-data-by-model-engine-type", name="get_car_data_by_model_engine_type")
     */
    public function getCarDataByModelAndEngineTypeAction(Request $request, SparePartAdvertDataProvider $provider)
    {
        $modelId = $request->query->get("model");
        $engineTypeItem = $request->query->get("engine_type");

        $model = $this->getDoctrine()->getRepository(Model::class)->find($modelId);
        $engineType = $this->getDoctrine()->getRepository(EngineType::class)->findOneBy(["type" => $engineTypeItem]);

        if(!$engineType){
            $engineType = $this->getDoctrine()->getRepository(EngineType::class)->find($engineTypeItem);
        }

        $data = [
            "engineCapacities" => $provider->getEngineCapacities($model, $engineType->getType()),
            "engineNames" => $provider->getEngineNames($model, $engineType->getType(), null),
            "countEngineNames" => $model instanceof Model ? count($model->getEngineNames($engineType->getType())) : 0,
        ];

        return new JsonResponse($data);
    }

    /**
     * @Route("/get-car-data-by-model-engine-type-capacity", name="get_car_data_by_model_engine_type_capacity")
     */
    public function getCarDataByModelAndEngineTypeCapacityAction(Request $request, SparePartAdvertDataProvider $provider)
    {
        $modelId = $request->query->get("model");
        $engineType = $request->query->get("engine_type");
        $capacity = $request->query->get("capacity");

        $model = $this->getDoctrine()->getRepository(Model::class)->find($modelId);
        $engineType = $this->getDoctrine()->getRepository(EngineType::class)->findOneBy(["type" => $engineType]);

        $data = [
            "engineNames" => $provider->getEngineNames($model, $engineType->getType(), $capacity),
            "countEngineNames" => $model instanceof Model ? count($model->getEngineNames($engineType->getType(), $capacity)) : 0,
        ];

        return new JsonResponse($data);
    }

    /**
     * @Route("/upload-user-photo-ajax", name="user_office_upload_user_photo_ajax")
     *
     * @throws ImageResizeException
     */
    public function uploadUserPhotoAjaxAction(Request $request, UserOfficeUploader $userOfficeUploader)
    {
        /** @var Client $client */
        $client = $this->getUser();
        /** @var FileUpload $uploader */
        $uploader = $this->get("app.file_upload");
        $uploadDir = $this->getParameter("upload_directory");

        $file = $request->files->get("file");
        $ip = $request->getClientIp();
        $coordinates = $request->request->get("coordinates");
        $coordinates = $coordinates ? json_decode($coordinates, true) : null;

        $uploader->setFolder(FileUpload::USER);
        $path = $uploader->upload($file);

        $userOfficeUploader->uploadImagesPersonOffice($path, $coordinates, $ip, $uploadDir,  $client);
        $this->getDoctrine()->getManager()->refresh($client);

        return new JsonResponse([
            "success" => true,
            "path" => '/images/' . $client->getPhoto()->getImage(),
        ]);
    }

    /**
     * @Route("/upload-business-profile-photo-ajax", name="user_office_upload_business_profile_photo_ajax")
     */
    public function uploadBusinessProfilePhotoAjaxAction(Request $request, GeoLocationProvider $provider)
    {
        /** @var Client $client */
        $client = $this->getUser();
        /** @var FileUpload $uploader */
        $uploader = $this->get("app.file_upload");
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $file = $request->files->get("file");
        $ip = $request->getClientIp();
        $coordinates = $request->request->get("coordinates");
        $coordinates = $coordinates ? json_decode($coordinates, true) : null;

        $uploader->setFolder(FileUpload::BUSINESS_PROFILE);
        $path = $uploader->upload($file);

        $image = new Image($path);
        $geoLocation = $provider->addGeoLocationToImage($coordinates, $ip);
        $image->setGeoLocation($geoLocation);

        $client->getSellerData()->setPhoto($image);

        $em->persist($image);
        $em->flush();

        return new JsonResponse([
            "success" => true,
            "path" => '/images/' . $image->getImage(),
        ]);
    }
}