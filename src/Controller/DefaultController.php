<?php

namespace App\Controller;

use App\Entity\Article\Article;
use App\Entity\Brand;
use App\Entity\City;
use App\Entity\Client\Client;
use App\Entity\Client\SellerCompany;
use App\Entity\General\MainPage;
use App\Entity\General\NotFoundPage;
use App\Entity\Model;
use App\Entity\SparePart;
use App\Type\ArticleFilterType;
use App\Type\PostsFilterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Route("/people", name="homepage_all_users")
     */
    public function showHomePageAction(Request $request)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        /** @var MainPage $homePage */
        $homePage = $this->getDoctrine()->getManager()->getRepository(MainPage::class)->findAll()[0];
        $filter = new PostsFilterType([], null, null, null, null);
        $route = $request->get('_route');

        $homePage->setFilteredTitle($route, $filter);
        $homePage->setFilteredDescription($route, $filter);

        $updatedArticles = $em->getRepository(Article::class)
            ->findAllByFilter(new ArticleFilterType(ArticleFilterType::SORT_UPDATED, [], 12));

        return $this->render('client/default/index.html.twig', [
            "homePage" => $homePage,
            "articles" => $route === "homepage_all_users" ? [] : $updatedArticles,
        ]);
    }

    /**
     * @Route("/user-posts/{userId}", name="homepage_filter_user", options={"expose"=true})
     * @Route("/car-posts/{urlBrand}", name="homepage_filter_brand", options={"expose"=true})
     * @Route("/car-posts/{urlBrand}/{urlModel}", name="homepage_filter_brand_model", options={"expose"=true})
     * @Route("/business-posts/{urlCity}/{urlActivity}", name="homepage_filter_city_activity", options={"expose"=true})
     * @Route("/people/user-posts/{userId}", name="homepage_filter_user_all_users", options={"expose"=true})
     * @Route("/people/car-posts/{urlBrand}", name="homepage_filter_brand_all_users", options={"expose"=true})
     * @Route("/people/car-posts/{urlBrand}/{urlModel}", name="homepage_filter_brand_model_all_users", options={"expose"=true})
     * @Route("/people/business-posts/{urlCity}/{urlActivity}", name="homepage_filter_city_activity_all_users", options={"expose"=true})
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
        $activity = $urlActivity && array_key_exists($urlActivity, SellerCompany::$activities) ? SellerCompany::$activities[$urlActivity] : null;
        $isAllUsers = strpos($route, "_all_users") !== false;
        $users = $user ?: ($isAllUsers ? '' : PostsFilterType::USERS_ACCESS_POST_HOMEPAGE);

        if($userId && !$user || $urlBrand && !$brand || $urlModel && !$model || $urlCity && !$city || $urlActivity && !$activity){
            throw new NotFoundHttpException(NotFoundPage::DEFAULT_MESSAGE);
        }

        $filter = new PostsFilterType($users, $brand, $model, $city, $activity);

        $homePage->setFilteredTitle($route, $filter);
        $homePage->setFilteredDescription($route, $filter);

        return $this->render('client/default/filtered-posts.html.twig', [
            "homePage" => $homePage,
        ]);
    }

    /**
     * @Route("/main-page-search-form", name="main_page_search_form", options={"expose"=true})
     */
    public function mainPageSearchFormAction(Request $request)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $requestData = json_decode($request->getContent(), true);

        /** @var Brand|null $brand */
        $brand = $em->getRepository(Brand::class)->findOneBy(["url" => $requestData["brand"]]);
        /** @var Model|null $model */
        $model = $em->getRepository(Model::class)->findOneBy([
            "brand" => $brand,
            "url" => $requestData["model"],
        ]);
        $sparePart = $em->getRepository(SparePart::class)->findOneBy(["url" => $requestData["sparePart"]]);
        $year = (int)$requestData["year"];
        $inStock = (bool)$requestData["inStock"];
        $redirectUrl = $this->generateUrl("show_brand_catalog_choice_brand");

        if(!$brand && $sparePart){
            $redirectUrl = $this->generateUrl("show_brand_catalog_choice_model", ["urlBrand" => $brand->getUrl()]);
        }
        elseif($brand && !$sparePart && !$model){
            $redirectUrl = $this->generateUrl("show_spare_part_catalog_choice_brand", ["urlSP" => $sparePart->getUrl()]);
        }
        elseif($brand && $model && !$sparePart){
            $redirectUrl = $this->generateUrl("show_brand_catalog_choice_spare_part", [
                "urlBrand" => $brand->getUrl(),
                "urlModel" => $model->getUrl(),
            ]);
        }
        elseif($brand && $model && $sparePart && !$inStock){
            $redirectUrl = $this->generateUrl("show_brand_catalog_choice_city", [
                "urlBrand" => $brand->getUrl(),
                "urlModel" => $model->getUrl(),
                "urlSP" => $sparePart->getUrl(),
            ]);
        }
        elseif($brand && $model && $sparePart && $inStock){
            $redirectUrl = $this->generateUrl("show_brand_catalog_final_page", [
                "urlBrand" => $brand->getUrl(),
                "urlModel" => $model->getUrl(),
                "urlSP" => $sparePart->getUrl(),
                "urlCity" => City::ALL_CITIES,
            ]);
        }

        return new JsonResponse(["redirectUrl" => $redirectUrl]);
    }

    /**
     * @Route("/search-by-voice-speech", name="search_by_voice_speech", options={"expose"=true})
     */
    public function searchByVoiceSpeechAction(Request $request)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $text = json_decode($request->getContent(), true)["text"];

        $brands = $em->getRepository(Brand::class)->findForSpeech($text);
        $spareParts = $em->getRepository(SparePart::class)->findForSpeech($text);



        return new JsonResponse(["redirectUrl" => count($spareParts)]);
    }
}