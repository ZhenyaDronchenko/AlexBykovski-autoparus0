<?php

namespace App\Controller\UserOffice\ProductCategories;

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
 * @Route("/import")
 *
 * @Security("has_role('ROLE_CLIENT')")
 */
class ImportController extends Controller
{
    /**
     * @Route("/ajax/check-is-correct-file/specific-adverts", name="import_ajax_check_is_correct_file_specific_adverts", options={"expose"=true})
     */
    public function checkIsCorrectFileImportSpecificAdvertsAction(Request $request)
    {
        return new JsonResponse([
            "file" => "name",
            "success" => "OK",
        ]);
    }
}