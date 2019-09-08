<?php

namespace App\Controller;

use App\Entity\Article\Article;
use App\Entity\Brand;
use App\Entity\City;
use App\Entity\Client\Client;
use App\Entity\Client\Post;
use App\Entity\Client\SellerCompany;
use App\Entity\General\MainPage;
use App\Entity\General\NotFoundPage;
use App\Entity\Model;
use App\Entity\SparePart;
use App\Type\ArticleFilterType;
use App\Type\PostsFilterType;
use Doctrine\ORM\EntityManagerInterface;
use SunCat\MobileDetectBundle\DeviceDetector\MobileDetector;
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
            ->findAllByFilter(new ArticleFilterType(ArticleFilterType::SORT_CREATED, [], 7, 0, false));

        $ourArticles = $em->getRepository(Article::class)
            ->findAllByFilter(new ArticleFilterType(ArticleFilterType::SORT_UPDATED, [], 3, 0, true));

        $businessPosts = $em->getRepository(Post::class)->findAllByFilter(new PostsFilterType(PostsFilterType::USERS_ACCESS_POST_HOMEPAGE, null, null, null, null, 4, 0));

        return $this->render('client/default/index.html.twig', [
            "homePage" => $homePage,
            "articles" => $route === "homepage_all_users" ? [] : $updatedArticles,
            "ourArticles" => $ourArticles,
            "businessPosts" => $businessPosts,
        ]);
    }

    /**
     * @Route("/interactiv", name="homepage_interactiv")
     * @Route("/interactiv/people", name="homepage_interactiv_all_users")
     */
    public function showHomePageInteractiveAction(Request $request)
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
            ->findAllByFilter(new ArticleFilterType(ArticleFilterType::SORT_UPDATED, [], 12, 0));

        return $this->render('client/default/interactiv.html.twig', [
            "homePage" => $homePage,
            "articles" => $route === "homepage_interactiv_all_users" ? [] : $updatedArticles
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
    public function interactivPageSearchFormAction(Request $request)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $requestData = json_decode($request->getContent(), true);
        $byName = isset($requestData["by-name"]) && $requestData["by-name"];

        /** @var Brand|null $brand */
        $brand = $em->getRepository(Brand::class)->findOneBy([
            $byName ? "name" : "url" => $requestData["brand"
            ]]);
        /** @var Model|null $model */
        $model = $em->getRepository(Model::class)->findOneBy([
            "brand" => $brand,
            $byName ? "name" : "url" => $requestData["model"],
        ]);
        $sparePart = $em->getRepository(SparePart::class)->findOneBy([$byName ? "name" : "url" => $requestData["sparePart"]]);
        $year = (int)$requestData["year"];
        $inStock = isset($requestData["inStock"]) && (bool)$requestData["inStock"];
        $redirectUrl = $this->generateUrl("show_brand_catalog_choice_brand");

        if($brand && !$sparePart && !$model){
            $redirectUrl = $this->generateUrl("show_brand_catalog_choice_model", ["urlBrand" => $brand->getUrl()]);
        }
        elseif(!$brand && $sparePart){
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
}