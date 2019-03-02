<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/page", name="universal_page")
 */
class UniversalPageController extends Controller
{
    /**
     * @Route("/00001/{url}", name="universal_page_brand")
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