<?php

namespace App\Controller;

use App\Entity\Advert\AutoSparePart\AutoSparePartSpecificAdvert;
use App\Entity\Article\Article;
use App\Entity\Brand;
use App\Entity\City;
use App\Entity\Client\Client;
use App\Entity\General\NotFoundPage;
use App\Entity\Model;
use App\Entity\SparePart;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class UserController extends Controller
{
    /**
     * @Route("/seller/{id}", name="seller_view")
     *
     * @ParamConverter("seller", class="App\Entity\Client\Client", options={"id" = "id"})
     */
    public function showSellerViewAction(Request $request, Client $seller)
    {
        if(!$seller->hasRole(User::ROLE_SELLER)){
            throw new NotFoundHttpException(NotFoundPage::DEFAULT_MESSAGE);
        }

        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $city = $em->getRepository(City::class)->findOneBy(["name" => $seller->getSellerData()->getSellerCompany()->getCity()]);
        $parseAdverts = [];
        $adverts = $seller->getSellerData()->getAdvertDetail()->getSpecificAdvertsSellerPage();

        /** @var AutoSparePartSpecificAdvert $advert */
        foreach ($adverts as $advert){
            $parseAdverts[] = [
                "item" => $advert,
                "sparePart" => $em->getRepository(SparePart::class)->findOneBy(["name" => $advert->getSparePart()]),
            ];
        }

        return $this->render('client/user/seller-view.html.twig', [
            "seller" => $seller,
            "city" => $city,
            "adverts" => $parseAdverts,
        ]);
    }

    /**
     * @Route("/seller/{urlCity}/{id}", name="seller_city_view")
     *
     * @ParamConverter("seller", class="App\Entity\Client\Client", options={"id" = "id"})
     */
    public function showSellerCityAction(Request $request, Client $seller)
    {
        return $this->render('client/user/seller-city-view.html.twig', []);
    }
}