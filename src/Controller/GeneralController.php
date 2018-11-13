<?php

namespace App\Controller;

use App\Entity\General\AboutGeneralPage;
use App\Entity\General\NewsGeneralPage;
use App\Entity\General\ToSellersGeneralPage;
use App\Entity\General\ToUsersGeneralPage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GeneralController extends Controller
{
    /**
     * @Route("/to_user", name="general_to_users_page")
     */
    public function showToUsersPageAction(Request $request)
    {
        return $this->render('client/general/to_users.html.twig', [
            "page" => $this->getDoctrine()->getRepository(ToUsersGeneralPage::class)->findAll()[0]
        ]);
    }

    /**
     * @Route("/to_seller", name="general_to_seller_page")
     */
    public function showToSellersPageAction(Request $request)
    {
        return $this->render('client/general/to_sellers.html.twig', [
            "page" => $this->getDoctrine()->getRepository(ToSellersGeneralPage::class)->findAll()[0]
        ]);
    }

    /**
     * @Route("/news", name="general_news_page")
     */
    public function showNewsPageAction(Request $request)
    {
        return $this->render('client/general/news.html.twig', [
            "page" => $this->getDoctrine()->getRepository(NewsGeneralPage::class)->findAll()[0]
        ]);
    }

    /**
     * @Route("/about", name="general_about_page")
     */
    public function showAboutPageAction(Request $request)
    {
        return $this->render('client/general/about.html.twig', [
            "page" => $this->getDoctrine()->getRepository(AboutGeneralPage::class)->findAll()[0]
        ]);
    }
}