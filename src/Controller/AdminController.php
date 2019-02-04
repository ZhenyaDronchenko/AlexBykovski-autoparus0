<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\City;
use App\Entity\Model;
use App\Entity\Phone\PhoneBrand;
use App\Entity\Phone\PhoneModel;
use App\Entity\Phone\PhoneSparePart;
use App\Entity\SparePart;
use App\Entity\User;
use App\Entity\UserData\UserEngine;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminController extends Controller
{
    /**
     * @Route("/authorize-as-user/{id}", name="admin_authorize_as_user")
     *
     * @ParamConverter("user", class="App:User", options={"id" = "id"})
     *
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function authorizeAdminAsUserAction(
        Request $request,
        User $user,
        UserPasswordEncoderInterface $encoder,
        TokenStorageInterface $tokenStorage
    )
    {
        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $tokenStorage->setToken($token);
        $request->getSession()->set('_security_main', serialize($token));

        return $this->redirectToRoute("homepage");
    }

    /**
     * @Route("/admin-remove-brand-logo/{id}", name="admin_remove_brand_logo")
     *
     * @ParamConverter("brand", class="App:Brand", options={"id" = "id"})
     *
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function removeBrandLogoAction(Request $request, Brand $brand)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $brand->setLogo(null);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/admin-remove-model-logo/{id}", name="admin_remove_model_logo")
     *
     * @ParamConverter("model", class="App:Model", options={"id" = "id"})
     *
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function removeModelLogoAction(Request $request, Model $model)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $model->setLogo(null);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/admin-remove-spare-part-logo/{id}", name="admin_remove_spare_part_logo")
     *
     * @ParamConverter("sparePart", class="App:SparePart", options={"id" = "id"})
     *
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function removeSparePartLogoAction(Request $request, SparePart $sparePart)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $sparePart->setLogo(null);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/admin-remove-city-logo/{id}", name="admin_remove_city_logo")
     *
     * @ParamConverter("city", class="App:City", options={"id" = "id"})
     *
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function removeCityLogoAction(Request $request, City $city)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $city->setLogo(null);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/admin-remove-phone-brand-logo/{id}", name="admin_remove_phone_brand_logo")
     *
     * @ParamConverter("brand", class="App\Entity\Phone\PhoneBrand", options={"id" = "id"})
     *
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function removePhoneBrandLogoAction(Request $request, PhoneBrand $brand)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $brand->setLogo(null);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/admin-remove-phone-model-logo/{id}", name="admin_remove_phone_model_logo")
     *
     * @ParamConverter("model", class="App\Entity\Phone\PhoneModel", options={"id" = "id"})
     *
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function removePhoneModelLogoAction(Request $request, PhoneModel $model)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $model->setLogo(null);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/admin-remove-phone-spare-part-logo/{id}", name="admin_remove_phone_spare_part_logo")
     *
     * @ParamConverter("sparePart", class="App\Entity\Phone\PhoneSparePart", options={"id" = "id"})
     *
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function removePhoneSparePartLogoAction(Request $request, PhoneSparePart $sparePart)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $sparePart->setLogo(null);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/admin-reject-user-engine/{id}", name="admin_reject_user_engine")
     *
     * @ParamConverter("engine", class="App\Entity\UserEngine", options={"id" = "id"})
     *
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function rejectUserEngineAction(Request $request, UserEngine $userEngine)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $em->remove($userEngine);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }
}