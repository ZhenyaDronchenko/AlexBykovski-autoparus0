<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\Model;
use App\Entity\Phone\PhoneBrand;
use App\Entity\Phone\PhoneModel;
use App\Entity\Phone\PhoneSparePart;
use App\Entity\SparePart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/search")
 */
class SearchController extends Controller
{
    const ALL_VARIANTS = "all_preload_variants";

    /**
     * @Route("/spare-part", name="search_spare_part_autocomplete")
     */
    public function searchSparePartAction(Request $request)
    {
        $text = $request->query->get("text");

        if(!is_string($text) || strlen($text) < 1){
            return new JsonResponse([]);
        }

        $text = $text !== self::ALL_VARIANTS ? $text : "";

        $spareParts = $this->getDoctrine()->getRepository(SparePart::class)->searchByText($text);

        $parsedSpareParts = [];

        /** @var SparePart $sparePart */
        foreach ($spareParts as $sparePart){
            $parsedSpareParts[] = $sparePart->toSearchArray();
        }

        return new JsonResponse($parsedSpareParts);
    }

    /**
     * @Route("/brand", name="search_brand_autocomplete")
     */
    public function searchBrandAction(Request $request)
    {
        $text = $request->query->get("text");

        if(!is_string($text) || strlen($text) < 1){
            return new JsonResponse([]);
        }

        $isAllVariants = $text === self::ALL_VARIANTS;

        $text = !$isAllVariants ? $text : "";

        $isRussianText = preg_match('/[А-Яа-яЁё]/u', $text);

        $brands = $this->getDoctrine()->getRepository(Brand::class)->searchByText($text, $isRussianText);

        $parsedBrands = [];

        /** @var Brand $brand */
        foreach ($brands as $brand){
            $parsedBrands[] = $brand->toSearchArray($isRussianText);

            if($isAllVariants){
                $parsedBrands[] = $brand->toSearchArray(!$isRussianText);
            }
        }

        return new JsonResponse($parsedBrands);
    }

    /**
     * @Route("/model/{urlBrand}", name="search_model_autocomplete")
     */
    public function searchModelAction(Request $request, $urlBrand)
    {
        $text = $request->query->get("text");

        $brand = $this->getDoctrine()->getRepository(Brand::class)->findOneBy(["url" => $urlBrand]);

        if(!is_string($text) || strlen($text) < 1 || !($brand instanceof Brand)){
            return new JsonResponse([]);
        }

        $isAllVariants = $text === self::ALL_VARIANTS;

        $text = !$isAllVariants ? $text : "";
        $isRussianText = preg_match('/[А-Яа-яЁё]/u', $text);

        $models = $this->getDoctrine()->getRepository(Model::class)->searchByText($text, $brand, $isRussianText);

        $parsedModels = [];

        /** @var Model $model */
        foreach ($models as $model){
            $parsedModels[] = $model->toSearchArray($isRussianText);

            if($isAllVariants){
                $parsedModels[] = $model->toSearchArray(!$isRussianText);
            }
        }

        return new JsonResponse($parsedModels);
    }

    /**
     * @Route("/phone/work", name="search_phone_work_autocomplete")
     */
    public function searchPhoneWorkAction(Request $request)
    {
        $text = $request->query->get("text");

        if(!is_string($text) || strlen($text) < 1){
            return new JsonResponse([]);
        }

        $spareParts = $this->getDoctrine()->getRepository(PhoneSparePart::class)->searchByWorkText($text);

        $parsedSpareParts = [];

        /** @var PhoneSparePart $sparePart */
        foreach ($spareParts as $sparePart){
            $parsedSpareParts[] = $sparePart->toWorkSearchArray();
        }

        return new JsonResponse($parsedSpareParts);
    }

    /**
     * @Route("/phone/brand", name="search_phone_brand_autocomplete")
     */
    public function searchPhoneBrandAction(Request $request)
    {
        $text = $request->query->get("text");

        if(!is_string($text) || strlen($text) < 1){
            return new JsonResponse([]);
        }

        $brands = $this->getDoctrine()->getRepository(PhoneBrand::class)->searchByText($text);

        $parsedBrands= [];

        /** @var PhoneBrand $brand */
        foreach ($brands as $brand){
            $parsedBrands[] = $brand->toSearchArray();
        }

        return new JsonResponse($parsedBrands);
    }

    /**
     * @Route("/phone/model/{urlBrand}", name="search_phone_model_autocomplete")
     */
    public function searchPhoneModelAction(Request $request, $urlBrand)
    {
        $text = $request->query->get("text");

        $brand = $this->getDoctrine()->getRepository(PhoneBrand::class)->findOneBy(["url" => $urlBrand]);

        if(!is_string($text) || strlen($text) < 1 || !($brand instanceof PhoneBrand)){
            return new JsonResponse([]);
        }

        $models = $this->getDoctrine()->getRepository(PhoneModel::class)->searchByText($text, $brand);

        $parsedModels= [];

        /** @var PhoneModel $model */
        foreach ($models as $model){
            $parsedModels[] = $model->toSearchArray();
        }

        return new JsonResponse($parsedModels);
    }
}