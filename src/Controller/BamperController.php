<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\City;
use App\Entity\Model;
use App\Entity\SparePart;
use App\Provider\Integration\BamperSuggestionProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/integration")
 */
class BamperController extends Controller
{
    /**
     * @Route("/get-ads/{urlBrand}/{urlModel}/{urlSP}/", name="show_suggestions_brand_model_spare_part", options={"expose"=true})
     * @Route("/get-ads/{urlBrand}/{urlModel}/{urlSP}/{urlCity}", name="show_suggestions_brand_model_spare_part_with_city", options={"expose"=true})
     */
    public function showSuggestionsForBrandModelSparePartAction(Request $request, $urlBrand, $urlModel, $urlSP, BamperSuggestionProvider $suggestionProvider)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $urlCity = $request->get("urlCity");

        $sparePart = $em->getRepository(SparePart::class)->findOneBy(["url" => $urlSP]);
        $brand = $em->getRepository(Brand::class)->findOneBy(["url" => $urlBrand]);
        $model = $em->getRepository(Model::class)->findOneBy(["url" => $urlModel]);
        $city = $urlCity ? $em->getRepository(City::class)->findOneBy(["url" => $urlCity]) : null;
        $isAjax = $request->query->has("ajax");

        $suggestions = [];

        if($sparePart instanceof SparePart && $brand instanceof Brand && $model instanceof Model){
            $inStock = $isAjax ? $request->query->has("in_stock") : true;
            $suggestions = $suggestionProvider->provide($brand, $model, $sparePart, $city, $inStock);
        }

        if($isAjax){
            return new JsonResponse($suggestions);
        }

        return $this->render('client/catalog/integration.html.twig', [
            'suggestions' => $suggestions,
        ]);
    }

    /**
     * @Route("/get-phone-number/", name="get_phone_number_by_url")
     */
    public function getPhoneNumberByUrlAction(Request $request, BamperSuggestionProvider $suggestionProvider)
    {
        $url = json_decode($request->getContent(), true)["url"];

        return new JsonResponse(["phones" => $suggestionProvider->getPhoneNumbers($url)]);
    }
}