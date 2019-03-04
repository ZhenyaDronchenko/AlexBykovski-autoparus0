<?php

namespace App\Controller\Catalog;

use App\Entity\Brand;
use App\Entity\Catalog\City\CatalogCityChoiceCity;
use App\Entity\City;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/city-catalog")
 */
class CityCatalogController extends Controller
{
    /**
     * @Route("/", name="show_city_catalog_choice_city")
     */
    public function showChoiceCityPageAction(Request $request)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $capital = $em->getRepository(City::class)->findOneBy(["type" => City::CAPITAL_TYPE]);
        $regionalCities = $em->getRepository(City::class)->findBy(["type" => City::REGIONAL_CITY_TYPE], ["name" => "ASC"]);
        $othersCities = $em->getRepository(City::class)->findBy([
            "type" => City::OTHERS_TYPE,
            "active" => true,
        ], ["name" => "ASC"]);

        $brands = $em->getRepository(Brand::class)->findBy([
            "active" => true,
            "popular" => true,
        ], ["name" => "ASC"]);

        return $this->render('client/catalog/city/choice-city.html.twig', [
            'capital' => $capital,
            'regionalCities' => $regionalCities,
            'othersCities' => $othersCities,
            'brands' => $brands,
            'page' => $em->getRepository(CatalogCityChoiceCity::class)->findAll()[0]
        ]);
    }

    /**
     * @Route("/{urlCity}", name="show_city_catalog_choice_brand")
     */
    public function showChoiceBrandPageAction(Request $request)
    {
        return $this->render('client/catalog/city/choice-brand.html.twig', []);
    }

    /**
     * @Route("/{urlCity}/{urlBrand}", name="show_city_catalog_choice_model")
     */
    public function showChoiceModelPageAction(Request $request)
    {
        return $this->render('client/catalog/city/choice-model.html.twig', []);
    }

    /**
     * @Route("/{urlCity}/{urlBrand}/{urlModel}", name="show_city_catalog_choice_year")
     */
    public function showChoiceYearPageAction(Request $request)
    {
        return $this->render('client/catalog/city/choice-year.html.twig', []);
    }
}