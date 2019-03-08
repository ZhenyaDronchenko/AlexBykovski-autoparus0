<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FineController extends Controller
{
    /**
     * @Route("/proverka-shtrafa", name="check_fine")
     */
    public function checkFineAction(Request $request)
    {
        return $this->render('client/fine/check-fine.html.twig', []);
    }

    /**
     * @Route("/proverka-shtrafa/{cityUrl}", name="check_fine_in_city")
     */
    public function checkFineInCityAction(Request $request)
    {
        return $this->render('client/fine/check-fine-city.html.twig', []);
    }
}