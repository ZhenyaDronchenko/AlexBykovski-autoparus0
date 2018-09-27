<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\Model;
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
    /**
     * @Route("/spare-part", name="search_spare_part_autocomplete")
     */
    public function searchSparePartAction(Request $request)
    {
        $text = $request->query->get("text");

        if(!is_string($text) || strlen($text) < 1){
            return new JsonResponse([]);
        }

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

        $brands = $this->getDoctrine()->getRepository(Brand::class)->searchByText($text);

        $parsedBrands= [];

        /** @var Brand $brand */
        foreach ($brands as $brand){
            $parsedBrands[] = $brand->toSearchArray();
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

        $models = $this->getDoctrine()->getRepository(Model::class)->searchByText($text, $brand);

        $parsedModels= [];

        /** @var Model $model */
        foreach ($models as $model){
            $parsedModels[] = $model->toSearchArray();
        }

        return new JsonResponse($parsedModels);
    }
}