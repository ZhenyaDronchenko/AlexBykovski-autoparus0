<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\City;
use App\Entity\Error\CodeOBD2Error;
use App\Entity\Model;
use App\Entity\Phone\PhoneBrand;
use App\Entity\Phone\PhoneModel;
use App\Entity\Phone\PhoneSparePart;
use App\Entity\SparePart;
use App\Entity\UniversalPage\UniversalPage;
use App\Entity\UniversalPage\UniversalPageBrand;
use App\Entity\UniversalPage\UniversalPageCity;
use App\Entity\UniversalPage\UniversalPageSparePart;
use App\Entity\User;
use App\Entity\UserData\UserEngine;
use App\Entity\UserData\UserOBD2ErrorCode;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Security("has_role('ROLE_ADMIN')")
 */
class AdminController extends Controller
{
    /**
     * @Route("/admin-remove-brand-logo/{id}", name="admin_remove_brand_logo")
     *
     * @ParamConverter("brand", class="App:Brand", options={"id" = "id"})
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
     */
    public function rejectUserEngineAction(Request $request, UserEngine $userEngine)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $em->remove($userEngine);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/admin-reject-user-obd2-error-code/{id}", name="admin_reject_user_obd2_error_code")
     *
     * @ParamConverter("engine", class="App\Entity\UserData\UserOBD2ErrorCode", options={"id" = "id"})
     */
    public function rejectUserOBD2ErrorCodeAction(Request $request, UserOBD2ErrorCode $userOBD2ErrorCode)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $em->remove($userOBD2ErrorCode);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/admin-approve-user-obd2-error-code/{id}", name="admin_approve_user_obd2_error_code")
     *
     * @ParamConverter("engine", class="App\Entity\UserData\UserOBD2ErrorCode", options={"id" = "id"})
     */
    public function approveUserOBD2ErrorCodeAction(Request $request, UserOBD2ErrorCode $userOBD2ErrorCode)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $type = $userOBD2ErrorCode->getType();
        $codeNumber = $userOBD2ErrorCode->getCode();

        $code = $em->getRepository(CodeOBD2Error::class)->findOneBy([
            "type" => $type,
            "code" => $codeNumber,
        ]);

        if(!($code instanceof CodeOBD2Error)){
            $code = new CodeOBD2Error();
            $code->setType($type);
            $code->setCode($codeNumber);
            $code->setCounter($userOBD2ErrorCode->getCounter());

            $em->persist($code);
        }
        else{
            $code->setCounter($code->getCounter() + $userOBD2ErrorCode->getCounter());
        }

        $em->remove($userOBD2ErrorCode);
        $em->flush();

        return $this->redirectToRoute("admin_app_error_typeobd2error_error_codeobd2error_edit", [
            "id" => $type->getId(),
            "childId" => $code->getId(),
        ]);
    }

    /**
     * @Route("/admin/copy-universal-page/{type}/{id}", name="admin_copy_universal_page")
     */
    public function copyUniversalPageAction(Request $request, $type, $id)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $redirectRoute = null;
        $page = null;

        switch ($type){
            case UniversalPage::UNIVERSAL_PAGE_BRAND:
                $redirectRoute = "admin_app_universalpage_universalpagebrand_edit";
                $page = $em->getRepository(UniversalPageBrand::class)->find($id);

                break;
            case UniversalPage::UNIVERSAL_PAGE_CITY:
                $redirectRoute = "admin_app_universalpage_universalpagecity_edit";
                $page = $em->getRepository(UniversalPageCity::class)->find($id);

                break;
            case UniversalPage::UNIVERSAL_PAGE_SPARE_PART:
                $redirectRoute = "admin_app_universalpage_universalpagesparepart_edit";
                $page = $em->getRepository(UniversalPageSparePart::class)->find($id);

                break;
            default:
                break;
        }

        if(!($page instanceof UniversalPage)){
            return $this->redirectToRoute("sonata_admin_redirect");
        }

        $newPage = $page->copy();

        $em->persist($newPage);
        $em->flush();
        $em->refresh($newPage);

        return $this->redirectToRoute($redirectRoute, [
            "id" => $newPage->getId(),
        ]);
    }
}