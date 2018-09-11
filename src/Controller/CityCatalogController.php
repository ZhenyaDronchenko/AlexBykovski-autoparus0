<?php

namespace App\Controller;


use App\Entity\Brand;
use App\Entity\CatalogCityChoiceCity;
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
    public function showCatalogAction(Request $request)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $capital = $em->getRepository(City::class)->findOneBy(["type" => City::CAPITAL_TYPE]);
        $regionalCities = $em->getRepository(City::class)->findBy(["type" => City::REGIONAL_CITY_TYPE], ["name" => "ASC"]);
        $othersCities = $em->getRepository(City::class)->findBy(["type" => City::OTHERS_TYPE], ["name" => "ASC"]);

        return $this->render('client/catalog/city/choice-city.html.twig', [
            'capital' => $capital,
            'regionalCities' => $regionalCities,
            'othersCities' => $othersCities,
            'page' => $em->getRepository(CatalogCityChoiceCity::class)->findAll()[0]
        ]);
    }
}