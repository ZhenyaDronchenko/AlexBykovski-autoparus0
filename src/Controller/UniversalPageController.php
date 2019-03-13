<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\City;
use App\Entity\General\MainPage;
use App\Entity\SparePart;
use App\Entity\UniversalPage\UniversalPageBrand;
use App\Entity\UniversalPage\UniversalPageCity;
use App\Entity\UniversalPage\UniversalPageSparePart;
use App\Provider\UniversalPageProvider;
use App\Transformer\VariableTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class UniversalPageController extends Controller
{
    /**
     * @Route("/page-brand/{id}", name="universal_page_brand")
     * @Route("/page-brand/{id}/{urlBrand}", name="universal_page_brand_specific_brand")
     *
     * @ParamConverter("page", class="App\Entity\UniversalPage\UniversalPageBrand", options={"id" = "id"})
     */
    public function showUniversalBrandPageAction(
        Request $request,
        UniversalPageBrand $page,
        UniversalPageProvider $universalPageProvider,
        VariableTransformer $transformer,
        $urlBrand = null
    )
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $brand = $urlBrand ? $em->getRepository(Brand::class)->findOneBy(["url" => $urlBrand]) : null;
        $transformerParameters = $brand ? [$brand] : [];

        return $this->render('client/universal-page/brand.html.twig', [
            "page" => $transformer->transformPage($page, $transformerParameters),
            "brand" => $brand,
            "titleHomepage" => $universalPageProvider->getMailPageTitle(),
            "models" => $brand instanceof Brand ? $universalPageProvider->getModels($brand) : [],
        ]);
    }

    /**
     * @Route("/page-city/{id}", name="universal_page_city")
     * @Route("/page-city/{id}/{urlCity}", name="universal_page_city_specific_city")
     *
     * @ParamConverter("page", class="App\Entity\UniversalPage\UniversalPageCity", options={"id" = "id"})
     */
    public function showUniversalCityPageAction(
        Request $request,
        UniversalPageCity $page,
        UniversalPageProvider $universalPageProvider,
        VariableTransformer $transformer,
        $urlCity = null
    )
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $city = $urlCity ? $em->getRepository(City::class)->findOneBy(["url" => $urlCity]) : null;
        $transformerParameters = $city ? [$city] : [];

        return $this->render('client/universal-page/city.html.twig', [
            "page" => $transformer->transformPage($page, $transformerParameters),
            "city" => $city,
            "titleHomepage" => $universalPageProvider->getMailPageTitle(),
            "brands" => $universalPageProvider->getBrands(),
        ]);
    }


    /**
     * @Route("/page-zapchasti/{id}", name="universal_page_spare_part")
     * @Route("/page-zapchasti/{id}/{urlSp}", name="universal_page_brand_specific_spare_part")
     *
     * @ParamConverter("page", class="App\Entity\UniversalPage\UniversalPageSparePart", options={"id" = "id"})
     */
    public function showUniversalSparePartPageAction(
        Request $request,
        UniversalPageSparePart $page,
        UniversalPageProvider $universalPageProvider,
        VariableTransformer $transformer,
        $urlSp = null
    )
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $sparePart = $urlSp ? $em->getRepository(SparePart::class)->findOneBy(["url" => $urlSp]) : null;
        $transformerParameters = $sparePart ? [$sparePart] : [];

        return $this->render('client/universal-page/spare-part.html.twig', [
            "page" => $transformer->transformPage($page, $transformerParameters),
            "sparePart" => $sparePart,
            "titleHomepage" => $universalPageProvider->getMailPageTitle(),
            "brands" => $universalPageProvider->getBrands(),
        ]);
    }
}