<?php

namespace App\Controller\UserOffice\ProductCategories;

use App\Entity\Client\Client;
use App\ImportAdvert\ImportChecker;
use App\ImportAdvert\ImportUploader;
use App\Upload\FileUpload;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
     * @Route("/ajax/upload-file/specific-adverts", name="import_ajax_upload_file_specific_adverts", options={"expose"=true})
     */
    public function uploadFileImportSpecificAdvertsAction(Request $request)
    {
        /** @var FileUpload $uploader */
        $uploader = $this->get("app.file_upload");
        $response = ["success" => false];
        $file = $request->files->get("file");

        if(!($file instanceof UploadedFile)){
            $response["errors"] = ["Файл не получен"];

            return new JsonResponse($response);
        }

        try {
            $uploader->setFolder(FileUpload::IMPORT_SPECIFIC_ADVERT);
            $uploader->setAllowMimeTypes([FileUpload::CSV_MIME_TYPE]);

            $path = $uploader->upload($file, null, $uploader->getFilePath($file, $this->getUser()->getId()));
        }
        catch (\Exception $exception){
            $response["errors"] = ["Серверная ошибка при загрузке файла"];

            return new JsonResponse($response);
        }

        return new JsonResponse([
            "file" => $path,
            "success" => true,
        ]);
    }

    /**
     * @Route("/ajax/check-is-correct-file/specific-adverts", name="import_ajax_check_is_correct_file_specific_adverts", options={"expose"=true})
     */
    public function checkIsCorrectFileImportSpecificAdvertsAction(Request $request, ImportChecker $importChecker)
    {
        $response = [
            "success" => false,
        ];

        $folder = $this->getParameter("upload_directory");
        $filePath = $folder . '/' . json_decode($request->getContent(), true)["path"];

        if(!file_exists($filePath)){
            $response["errors"] = ["Файл не найден. Обратитесь в техподдержку"];

            return new JsonResponse($response);
        }

        return new JsonResponse($importChecker->isCorrectFileToImportSpecificAdvert($filePath));
    }

    /**
     * @Route("/ajax/import-file/specific-adverts", name="import_ajax_import_file_specific_adverts", options={"expose"=true})
     */
    public function importFileSpecificAdvertsAction(Request $request, ImportUploader $importer)
    {
        $response = [
            "success" => false,
        ];
        /** @var Client $client */
        $client = $this->getUser();

        $folder = $this->getParameter("upload_directory");
        $filePath = $folder . '/' . json_decode($request->getContent(), true)["path"];

        if(!file_exists($filePath)){
            $response["errors"] = ["Файл не найден. Обратитесь в техподдержку"];

            return new JsonResponse($response);
        }

        return new JsonResponse($importer->importFile($filePath, $client->getSellerData()->getAdvertDetail()));
    }
}