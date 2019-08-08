<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\City;
use App\Entity\Error\CodeOBD2Error;
use App\Entity\Model;
use App\Entity\SparePart;
use App\Entity\UniversalPage\UniversalPage;
use App\Entity\UniversalPage\UniversalPageBrand;
use App\Entity\UniversalPage\UniversalPageCity;
use App\Entity\UniversalPage\UniversalPageSparePart;
use App\Entity\User;
use App\Entity\UserData\ImportAdvertError;
use App\Entity\UserData\ImportAdvertFile;
use App\Entity\UserData\UserEngine;
use App\Entity\UserData\UserOBD2ErrorCode;
use App\Handler\SaveKeywordsHandler;
use App\ImportAdvert\ImportUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    /**
     * @Route("/admin-reject-user-obd2-error-code/{id}", name="admin_reject_user_obd2_error_code")
     *
     * @ParamConverter("engine", class="App\Entity\UserData\UserOBD2ErrorCode", options={"id" = "id"})
     *
     * @Security("has_role('ROLE_ADMIN')")
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
     *
     * @Security("has_role('ROLE_ADMIN')")
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
     *
     * @Security("has_role('ROLE_ADMIN')")
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

    /**
     * @Route("/admin/ajax/toggle-role/{id}", name="admin_ajax_toggle_role_for_user")
     *
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @ParamConverter("user", class="App\Entity\User", options={"id" = "id"})
     */
    public function ajaxToggleRoleForUserAction(Request $request, User $user)
    {
        $role = $request->get("role");

        if(!$user || !$role){
            return new JsonResponse(["success" => false]);
        }

        $user->toggleRole($role);

        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse([
            "success" => true,
        ]);
    }

    /**
     * @Route("/admin/ajax/get-models-by-brand/{id}", name="admin_ajax_get_models_by_brand", options={"expose"=true})
     *
     * @ParamConverter("brand", class="App\Entity\Brand", options={"id" = "id"})
     *
     * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_ADMIN_ARTICLE_WRITER')")
     */
    public function getModelsByBrandAction(Request $request, Brand $brand)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $models = $em->getRepository(Model::class)->findBy(["brand" => $brand]);

        $parsedModels = [];

        /** @var Model $model */
        foreach ($models as $model){
            $parsedModels[$model->getId()] = $model->getName();
        }

        return new JsonResponse($parsedModels);
    }

    /**
     * @Route("/admin-remove-import-advert-error/", name="admin_remove_import_advert_error")
     *
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function removeImportAdvertErrorAction(Request $request)
    {
        $ids = explode(',', $request->query->get("ids"));

        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $em->getRepository(ImportAdvertError::class)->deleteByIds($ids);

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/admin-save-keywords-import-advert-error/{id}", name="admin_save_keywords_import_advert_error")
     *
     * @ParamConverter("error", class="App\Entity\UserData\ImportAdvertError", options={"id" = "id"})
     *
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function saveKeywordsImportAdvertErrorAction(Request $request, ImportAdvertError $error, SaveKeywordsHandler $handler)
    {
        $value = $request->request->get("value");
        $type = $request->request->get("type");
        $em = $this->getDoctrine()->getManager();

        $handler->saveKeywords($type, $value, $error);

        $em->remove($error);
        $em->flush();

        return new JsonResponse(["success" => true]);
    }

    /**
     * @Route("/admin-import-file-specific-advert/{id}", name="admin_import_file_specific_advert")
     *
     * @ParamConverter("file", class="App\Entity\UserData\ImportAdvertFile", options={"id" = "id"})
     *
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function importFileSpecificAdvertAction(Request $request, ImportAdvertFile $file, ImportUploader $importer)
    {
        ini_set('max_execution_time', 10*60);
        ini_set('memory_limit', "512M");

        $isSave = $request->request->get("isSave", false) === "true";

        $folder = rtrim($this->getParameter("public_folder"), '/');
        $filePath = $folder . $file->getPath();

        $importer->setSaveMode($isSave);

        return new JsonResponse($importer->importFile($filePath, $file->getSellerAdvertDetail()));
    }

    /**
     * @Route("/admin-remove-file-specific-advert/{id}", name="admin_remove_file_specific_advert")
     *
     * @ParamConverter("file", class="App\Entity\UserData\ImportAdvertFile", options={"id" = "id"})
     *
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function removeFileSpecificAdvertAction(Request $request, ImportAdvertFile $file)
    {
        $folder = rtrim($this->getParameter("public_folder"), '/');
        $filePath = $folder . $file->getPath();
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $em->remove($file);
        $em->flush();

        if(file_exists($filePath)){
            unset($filePath);
        }

        return new JsonResponse([
            "status" => true,
        ]);
    }
}