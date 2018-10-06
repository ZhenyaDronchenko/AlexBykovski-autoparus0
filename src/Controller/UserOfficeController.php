<?php

namespace App\Controller;

use App\Entity\Client\Client;
use App\Entity\User;
use App\Form\Type\PersonalDataType;
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
        return $this->render('client/user-office/user-base-profile.html.twig', [
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
    public function editPersonalData(Request $request)
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
}