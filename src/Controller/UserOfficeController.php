<?php

namespace App\Controller;

use App\Entity\Client\Client;
use App\Entity\Client\UserCar;
use App\Entity\User;
use App\Form\Type\ClientCarsType;
use App\Form\Type\PersonalDataType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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

        $formPersonalData = $this->createForm(PersonalDataType::class, $client);
        $formCars = $this->createForm(ClientCarsType::class, $client, ["isFormSubmitted" => false]);

        return $this->render('client/user-office/user-base-profile.html.twig', [
            "formPersonalData" => $formPersonalData->createView(),
            "formCars" => $formCars->createView(),
        ]);
    }

    /**
     * @Route("/business-profile", name="show_user_business_office")
     *
     * @Security("has_role('ROLE_SELLER')")
     */
    public function editUserBusinessOfficeAction(Request $request)
    {
        return $this->render('client/user-office/user-business-profile.html.twig', [
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
        ini_set('xdebug.var_display_max_depth', '10');
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
}