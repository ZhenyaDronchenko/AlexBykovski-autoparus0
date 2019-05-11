<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\City;
use App\Entity\Client\Client;
use App\Entity\Client\SellerCompany;
use App\Entity\General\MainPage;
use App\Entity\Model;
use App\Type\PostsFilterType;
use Doctrine\ORM\EntityManagerInterface;
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
        return $this->render('client/default/index.html.twig', [
            "homePage" => $this->getDoctrine()->getManager()->getRepository(MainPage::class)->findAll()[0],
        ]);
    }

    /**
     * @Route("/user-posts/{userId}", name="homepage_filter_user", options={"expose"=true})
     * @Route("/car-posts/{urlBrand}", name="homepage_filter_brand", options={"expose"=true})
     * @Route("/car-posts/{urlBrand}/{urlModel}", name="homepage_filter_brand_model", options={"expose"=true})
     * @Route("/business-posts/{urlCity}/{urlActivity}", name="homepage_filter_city_activity", options={"expose"=true})
     */
    public function showHomePageWithFilteredPostsAction(
        Request $request,
        $userId = null,
        $urlBrand = null,
        $urlModel = null,
        $urlCity = null,
        $urlActivity = null
    )
    {
        $route = $request->get('_route');
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        /** @var MainPage $homePage */
        $homePage = $em->getRepository(MainPage::class)->findAll()[0];
        $user = $userId ? $em->getRepository(Client::class)->find($userId) : null;
        $brand = $em->getRepository(Brand::class)->findOneBy(["url" => $urlBrand]);
        $model = $em->getRepository(Model::class)->findOneBy(["url" => $urlModel]);
        $city = $em->getRepository(City::class)->findOneBy(["url" => $urlCity]);
        $activity = array_key_exists($urlActivity, SellerCompany::$activities) ? SellerCompany::$activities[$urlActivity] : null;
        $filter = new PostsFilterType($user, $brand, $model, $city, $activity);

        $homePage->setFilteredTitle($route, $filter);
        $homePage->setFilteredDescription($route, $filter);

        return $this->render('client/default/filtered-posts.html.twig', [
            "homePage" => $homePage,
        ]);
    }
}