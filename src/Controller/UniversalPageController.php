<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\Catalog\City\CatalogCityChoiceModel;
use App\Entity\Catalog\SparePart\CatalogSparePartChoiceModel;
use App\Entity\City;
use App\Entity\General\MainPage;
use App\Entity\SparePart;
use App\Entity\UniversalPage\UniversalPageBrand;
use App\Entity\UniversalPage\UniversalPageCity;
use App\Entity\UniversalPage\UniversalPageSparePart;
use App\Provider\TitleProvider;
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
        TitleProvider $titleProvider,
        $urlBrand = null
    )
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $brand = $urlBrand ? $em->getRepository(Brand::class)->findOneBy(["url" => $urlBrand]) : null;
        $transformerParameters = $brand ? [$brand] : [];
        $unParsedBrands = $em->getRepository(Brand::class)->findBy(["active" => true], ["name" => "ASC"]);
        $brands = [];

        foreach ($unParsedBrands as $unParsedBrand){
            $brands[] = [
                "item" => $universalPageProvider->brandToArray($unParsedBrand),
                "title" => $titleProvider->getSinglePageTitle(UniversalPageBrand::class, [$unParsedBrand]),
            ];
        }

        return $this->render('client/universal-page/brand.html.twig', [
            "page" => $transformer->transformPage($page, $transformerParameters),
            "previousPageTitle" => $titleProvider->getSinglePageTitle(UniversalPageBrand::class),
            "brand" => $brand,
            "titleHomepage" => $titleProvider->getSinglePageTitle(MainPage::class),
            "models" => $brand instanceof Brand ? $universalPageProvider->getModels($brand) : [],
            "brands" => $brands,
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
        TitleProvider $titleProvider,
        $urlCity = null
    )
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $city = $urlCity ? $em->getRepository(City::class)->findOneBy(["url" => $urlCity]) : null;
        $transformerParameters = $city ? [$city] : [];

        $unParsedBrands = $em->getRepository(Brand::class)->findBy(["popular" => true], ["name" => "ASC"]);
        $unParsedCapital = $em->getRepository(City::class)->findBy(["type" => City::CAPITAL_TYPE]);
        $unParsedOtherCities = $em->getRepository(City::class)->findBy(["type" => City::REGIONAL_CITY_TYPE], ["name" => "ASC"]);
        $unParsedCities = array_merge($unParsedCapital, $unParsedOtherCities);
        $brands = [];
        $cities = [];

        foreach ($unParsedBrands as $unParsedBrand){
            $brands[] = [
                "item" => $universalPageProvider->brandToArray($unParsedBrand),
                "title" => $titleProvider->getSinglePageTitle(CatalogCityChoiceModel::class, [$unParsedBrand, $city]),
            ];
        }

        foreach ($unParsedCities as $unParsedCity){
            $cities[] = [
                "item" => $universalPageProvider->cityToArray($unParsedCity),
                "title" => $titleProvider->getSinglePageTitle(UniversalPageCity::class, [$unParsedCity]),
            ];
        }

        return $this->render('client/universal-page/city.html.twig', [
            "page" => $transformer->transformPage($page, $transformerParameters),
            "city" => $city,
            "titleHomepage" => $titleProvider->getSinglePageTitle(MainPage::class),
            "previousPageTitle" => $titleProvider->getSinglePageTitle(UniversalPageCity::class),
            "brands" => $brands,
            "cities" => $cities,
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
        TitleProvider $titleProvider,
        $urlSp = null
    )
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $sparePart = $urlSp ? $em->getRepository(SparePart::class)->findOneBy(["url" => $urlSp]) : null;
        $transformerParameters = $sparePart ? [$sparePart] : [];

        $unParsedSpareParts = $em->getRepository(SparePart::class)->findBy(["popular" => true], ["name" => "ASC"]);
        $unParsedBrands = $em->getRepository(Brand::class)->findBy(["popular" => true], ["name" => "ASC"]);
        $brands = [];
        $spareParts = [];

        foreach ($unParsedSpareParts as $unParsedSparePart){
            $spareParts[] = [
                "item" => $universalPageProvider->sparePartToArray($unParsedSparePart),
                "title" => $titleProvider->getSinglePageTitle(UniversalPageSparePart::class, [$unParsedSparePart]),
            ];
        }

        foreach ($unParsedBrands as $unParsedBrand){
            $brands[] = [
                "item" => $universalPageProvider->brandToArray($unParsedBrand),
                "title" => $titleProvider->getSinglePageTitle(CatalogSparePartChoiceModel::class, [$unParsedBrand, $sparePart]),
            ];
        }

        return $this->render('client/universal-page/spare-part.html.twig', [
            "page" => $transformer->transformPage($page, $transformerParameters),
            "sparePart" => $sparePart,
            "titleHomepage" => $titleProvider->getSinglePageTitle(MainPage::class),
            "previousPageTitle" => $titleProvider->getSinglePageTitle(UniversalPageSparePart::class),
            "brands" => $brands,
            "spareParts" => $spareParts,
        ]);
    }
}