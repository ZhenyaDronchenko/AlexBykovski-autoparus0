<?php

namespace App\Controller;

use App\Entity\Brand;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CatalogController extends Controller
{
    /**
     * @Route("/catalog", name="show_catalog")
     */
    public function showCatalogAction(Request $request)
    {
        return $this->render('client/catalog/catalog.html.twig', [
            'brands' => $this->getDoctrine()->getRepository(Brand::class)->findBy(["active" => true])
        ]);
    }

    /**
     * @Route("/cities", name="show_cities")
     */
    public function showCitiesAction(Request $request)
    {
        return $this->render('client/catalog/cities.html.twig', [
        ]);
    }
}