<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\Client\Client;
use App\Entity\Client\SellerCompany;
use App\Entity\Client\SellerCompanyWorkflow;
use App\Entity\Client\SellerData;
use App\Entity\EngineType;
use App\Entity\Model;
use App\Entity\User;
use App\Form\Type\ClientCarsType;
use App\Form\Type\PersonalDataType;
use App\Form\Type\SellerCompanyType;
use App\Provider\Form\ClientCarProvider;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
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
        return $this->render('client/user-office/user-office.html.twig', [
        ]);
    }

    /**
     * @Route("/base-profile", name="show_user_base_profile")
     */
    public function editUserBaseProfileAction(Request $request)
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

        $formPersonalData = $this->createForm(PersonalDataType::class, $client);
        $formCars = $this->createForm(ClientCarsType::class, $client, ["isFormSubmitted" => false]);
        $formBusiness = $this->createForm(SellerCompanyType::class, $sellerCompany);

        return $this->render('client/user-office/user-base-profile.html.twig', [
            "formPersonalData" => $formPersonalData->createView(),
            "formCars" => $formCars->createView(),
            "formBusiness" => $formBusiness->createView(),
        ]);
    }

    /**
     * @Route("/business-profile", name="show_user_business_office")
     */
    public function editUserBusinessOfficeAction(Request $request)
    {
        /** @var Client $client */
        $client = $this->getUser();

        if(!$client->hasRole(User::ROLE_SELLER)){
            return $this->redirectToRoute("show_user_office");
        }

        return $this->render('client/user-office/user-business-profile.html.twig', $this->handleBusinessForm($request));
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
            $client->setPhone(str_replace(' ', '', $client->getPhone()));
            $isValid = true;

            $em->flush();
        }

        return $this->render('client/user-office/base-profile/personal-data.html.twig', [
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

            $em->flush();

            $em->refresh($client);
        }

        $form = $this->createForm(ClientCarsType::class, $client, ["isFormSubmitted" => false]);

        return $this->render('client/user-office/base-profile/cars.html.twig', [
            "form" => $form->createView(),
            "isValid" => $isValid
        ]);
    }

    /**
     * @Route("/edit-business-profile", name="edit_user_business_profile")
     */
    public function editBusinessProfileAction(Request $request)
    {
        $parameters = $this->handleBusinessForm($request);

        if($parameters["isValid"]){
            return new JsonResponse([
                "success" => true,
                "redirect" => $this->generateUrl("show_user_office")
            ]);
        }

        return $this->render('client/user-office/base-profile/business-profile.html.twig', $parameters);
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
    public function getCapacitiesByModelEngineTypeAction(Request $request, ClientCarProvider $provider){
        $modelId = $request->query->get("model");
        $engineTypeId = $request->query->get("engine_type");

        $model = $this->getDoctrine()->getRepository(Model::class)->find($modelId);
        $engineType = $this->getDoctrine()->getRepository(EngineType::class)->find($engineTypeId);

        return new JsonResponse($provider->getCapacities($model, $engineType));
    }

    private function handleBusinessForm(Request $request)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
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

        $form->handleRequest($request);

        $isValid = false;

        if($form->isSubmitted() && $form->isValid()){
            $isValid = true;

            if(!$client->isSeller()){
                $sellerData = new SellerData($client);
                $sellerData->setClient($client);
                $sellerData->setSellerCompany($sellerCompany);
                $client->setSellerData($sellerData);

                $em->persist($sellerCompany);
                $em->persist($sellerData);
                $em->persist($sellerCompany->getWorkflow());
            }

            $em->flush();
        }

        return [
            "form" => $form->createView(),
            "isValid" => $isValid
        ];
    }
}