<?php

namespace App\Controller;

use App\Entity\Article\Article;
use App\Entity\Brand;
use App\Entity\City;
use App\Entity\Client\Client;
use App\Entity\General\NotFoundPage;
use App\Entity\Model;
use App\Entity\SparePart;
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
        return $this->render('client/user/seller-view.html.twig', []);
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