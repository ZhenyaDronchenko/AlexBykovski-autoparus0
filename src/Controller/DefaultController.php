<?php

namespace App\Controller;

use App\Entity\General\MainPage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function showHomePageAction(Request $request)
    {
        $homePage = $this->getDoctrine()->getRepository(MainPage::class)->findAll()[0];

        return $this->render('client/default/index.html.twig', [
            "homePage" => $homePage
        ]);
    }
}