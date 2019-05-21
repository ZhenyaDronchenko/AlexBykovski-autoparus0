<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\City;
use App\Entity\Client\Client;
use App\Entity\Client\GalleryPhoto;
use App\Entity\Client\SellerCompany;
use App\Entity\Model;
use App\Entity\Phone\PhoneBrand;
use App\Entity\Phone\PhoneModel;
use App\Entity\Phone\PhoneSparePart;
use App\Entity\SparePart;
use App\Type\PostsFilterType;
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

    /**
     * @Route("/posts", name="search_posts")
     */
    public function searchPostsAction(Request $request)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $requestData = json_decode($request->getContent(), true);
        $offset = isset($requestData["offset"]) ? $requestData["offset"] : null;
        $limit = isset($requestData["limit"]) ? $requestData["limit"] : null;

        $user = isset($requestData["userId"]) ? $em->getRepository(Client::class)->find($requestData["userId"]) : null;
        $allUsers = isset($requestData["allUsers"]) && $requestData["allUsers"];
        $brand = isset($requestData["urlBrand"]) ? $em->getRepository(Brand::class)->findOneBy(["url" => $requestData["urlBrand"]]) : null;
        $model = isset($requestData["urlModel"]) ? $em->getRepository(Model::class)->findOneBy(["url" => $requestData["urlModel"]]) : null;
        $city = isset($requestData["urlCity"]) ? $em->getRepository(City::class)->findOneBy(["url" => $requestData["urlCity"]]) : null;
        $activity = isset($requestData["urlActivity"]) && array_key_exists($requestData["urlActivity"], SellerCompany::$activities) ?
            SellerCompany::$activities[$requestData["urlActivity"]] : null;
        $users = $user ?: ($allUsers ? [] : PostsFilterType::ADMINS);
        $filter = new PostsFilterType($users, $brand, $model, $city, $activity, $limit, $offset);

        if(!is_numeric($offset) || !is_numeric($limit)){
            return new JsonResponse([]);
        }

        $photos = $em->getRepository(GalleryPhoto::class)->findAllByFilter($filter);

        $parsedPhotos= [];

        /** @var GalleryPhoto $photo */
        foreach ($photos as $photo){
            $parsedPhoto = $photo->toSearchArray();

            if($parsedPhoto["city"]){
                $city = $em->getRepository(City::class)->findOneBy(["name" => $parsedPhoto["city"]]);
                $parsedPhoto["city"] = $city ? $city->getUrl() : null;
            }

            if($parsedPhoto["brand"]){
                $brand = $em->getRepository(Brand::class)->findOneBy(["name" => $parsedPhoto["brand"]]);
                $parsedPhoto["brand"] = $brand ? $brand->getUrl() : null;
            }

            if($parsedPhoto["model"]){
                $model = $em->getRepository(Model::class)->findOneBy(["name" => $parsedPhoto["model"]]);
                $parsedPhoto["model"] = $model ? $model->getUrl() : null;
            }

            $parsedPhotos[] = $parsedPhoto;
        }

        return new JsonResponse($parsedPhotos);
    }
}