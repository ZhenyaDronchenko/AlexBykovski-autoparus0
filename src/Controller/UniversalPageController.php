<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UniversalPageController extends Controller
{
    /**
     * @Route("/page-brand/{id}", name="universal_page_brand")
     * @Route("/page-brand/{id}/{urlBrand}", name="universal_page_brand_specific_brand")
     */
    public function showUniversalBrandPageAction(Request $request)
    {
        return $this->render('client/universal-page/brand.html.twig', [
        ]);
    }

    /**
     * @Route("/city/{url}", name="universal_page_city")
     */
    public function showUniversalCityPageAction(Request $request)
    {
        return $this->render('client/universal-page/city.html.twig', [
        ]);
    }

    /**
     * @Route("/zapchasti/{url}", name="universal_page_spare-part")
     */
    public function showUniversalSparePartPageAction(Request $request)
    {
        return $this->render('client/universal-page/spare-part.html.twig', [
        ]);
    }
}