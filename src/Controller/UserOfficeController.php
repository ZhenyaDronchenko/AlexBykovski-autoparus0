<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/user-office")
 *
 * @Security("has_role('ROLE_BUYER') or has_role('ROLE_SELLER')")
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
     */
    public function editUserBusinessOfficeAction(Request $request)
    {
        return $this->render('client/user-office/user-business-profile.html.twig', [
        ]);
    }
}