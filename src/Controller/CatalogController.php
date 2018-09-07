<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\CatalogGeneralPage;
use Doctrine\ORM\EntityManagerInterface;
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
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $allBrands = $em->getRepository(Brand::class)->findBy(["active" => true], ["name" => "ASC"]);
        $popularBrands = array_filter($allBrands, function(Brand $brand){
            return $brand->isPopular();
        });

        return $this->render('client/catalog/brand/catalog.html.twig', [
            'allBrands' => $allBrands,
            'popularBrands' => $popularBrands,
            'page' => $em->getRepository(CatalogGeneralPage::class)->findAll()[0]
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