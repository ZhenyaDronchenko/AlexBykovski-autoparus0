<?php

namespace App\Controller;

use App\Entity\General\AboutGeneralPage;
use App\Entity\General\ContactUsGeneralPage;
use App\Entity\General\NewsGeneralPage;
use App\Entity\General\ToSellersGeneralPage;
use App\Entity\General\ToUsersGeneralPage;
use App\Entity\General\UserAgreementPage;
use App\Provider\InfoPageProvider;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GeneralController extends Controller
{
    /**
     * @Route("/to_user", name="general_to_users_page")
     */
    public function showToUsersPageAction(Request $request, InfoPageProvider $provider)
    {
        $page = $this->getDoctrine()->getRepository(ToUsersGeneralPage::class)->findAll()[0];

        return $this->render('client/general/info-base.html.twig', [
            "page" => $page,
            "pageName" => "Пользователям",
            "cities" => $provider->getCities($page),
            "brands" => $provider->getBrands(),
        ]);
    }

    /**
     * @Route("/to_seller", name="general_to_seller_page")
     */
    public function showToSellersPageAction(Request $request, InfoPageProvider $provider)
    {
        $page = $this->getDoctrine()->getRepository(ToSellersGeneralPage::class)->findAll()[0];

        return $this->render('client/general/info-base.html.twig', [
            "page" => $page,
            "pageName" => "Продавцам",
            "cities" => $provider->getCities($page),
            "brands" => $provider->getBrands(),
        ]);
    }

    /**
     * @Route("/news", name="general_news_page")
     */
    public function showNewsPageAction(Request $request, InfoPageProvider $provider)
    {
        $page = $this->getDoctrine()->getRepository(NewsGeneralPage::class)->findAll()[0];

        return $this->render('client/general/info-base.html.twig', [
            "page" => $page,
            "pageName" => "Новости",
            "cities" => $provider->getCities($page),
            "brands" => $provider->getBrands(),
        ]);
    }

    /**
     * @Route("/about", name="general_about_page", options={"expose"=true})
     */
    public function showAboutPageAction(Request $request, InfoPageProvider $provider)
    {
        $page = $this->getDoctrine()->getRepository(AboutGeneralPage::class)->findAll()[0];

        return $this->render('client/general/info-base.html.twig', [
            "page" => $page,
            "pageName" => "Обратная связь",
            "cities" => $provider->getCities($page),
            "brands" => $provider->getBrands(),
        ]);
    }

    /**
     * @Route("/contact-us", name="general_contact_us_page", options={"expose"=true})
     */
    public function showContactUsPageAction(Request $request, InfoPageProvider $provider)
    {
        $page = $this->getDoctrine()->getRepository(ContactUsGeneralPage::class)->findAll()[0];

        return $this->render('client/general/info-base.html.twig', [
            "page" => $page,
            "pageName" => "О нас",
            "cities" => $provider->getCities($page),
            "brands" => $provider->getBrands(),
        ]);
    }

    /**
     * @Route("/user-agreement", name="user_agreement")
     */
    public function showUserAgreementPageAction(Request $request)
    {
        return $this->render('client/general/user-agreement.html.twig', [
            "page" => $this->getDoctrine()->getRepository(UserAgreementPage::class)->findAll()[0],
        ]);
    }
}