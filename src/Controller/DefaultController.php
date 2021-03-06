<?php

namespace App\Controller;

use App\Entity\Article\Article;
use App\Entity\Article\ArticleTheme;
use App\Entity\Article\ArticleType;
use App\Entity\Brand;
use App\Entity\City;
use App\Entity\Client\Client;
use App\Entity\Client\Post;
use App\Entity\Client\SellerCompany;
use App\Entity\Error\CodeOBD2Error;
use App\Entity\Error\TypeOBD2Error;
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
     * @Route("/new-main", name="new_homepage")
     * @Route("/new-people", name="new_homepage_all_users")
     */
    public function showNewHomePageAction(Request $request)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        /** @var MainPage $homePage */
        $homePage = $this->getDoctrine()->getManager()->getRepository(MainPage::class)->findAll()[0];
        $filter = new PostsFilterType([], null, null, null, null);
        $route = $request->get('_route');

        $homePage->setFilteredTitle($route, $filter);
        $homePage->setFilteredDescription($route, $filter);

        $newsTheme = $em->getRepository(ArticleTheme::class)->findOneBy(["url" => ArticleTheme::NEWS_THEME]);
        $ourType = $em->getRepository(ArticleType::class)->findOneBy(["type" => ArticleType::OUR_UNIQUE_MATERIAL]);

        $updatedArticles = $em->getRepository(Article::class)
            ->findAllByFilter(new ArticleFilterType(ArticleFilterType::SORT_CREATED, [$newsTheme], 7, 0, [], [], [$ourType]));

        $ourArticles = $em->getRepository(Article::class)
            ->findAllByFilter(new ArticleFilterType(ArticleFilterType::SORT_UPDATED, [], 12, 0, [$ourType]));

        $notOurNotNews = $em->getRepository(Article::class)
            ->findAllByFilter(new ArticleFilterType(ArticleFilterType::SORT_UPDATED, [], 6, 0, [], [$newsTheme], [$ourType]));

        $businessPosts = $em->getRepository(Post::class)->findAllByFilter(new PostsFilterType(PostsFilterType::USERS_ACCESS_POST_HOMEPAGE, null, null, null, null, 4, 0));

        return $this->render('client/default/index-new.html.twig', [
            "homePage" => $homePage,
            "articles" => $route === "homepage_all_users" ? [] : $updatedArticles,
            "ourArticles" => $ourArticles,
            "notOurNotNews" => $notOurNotNews,
            "businessPosts" => $businessPosts,
        ]);
    }

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

        $newsTheme = $em->getRepository(ArticleTheme::class)->findOneBy(["url" => ArticleTheme::NEWS_THEME]);
        $ourType = $em->getRepository(ArticleType::class)->findOneBy(["type" => ArticleType::OUR_UNIQUE_MATERIAL]);

        $updatedArticles = $em->getRepository(Article::class)
            ->findAllByFilter(new ArticleFilterType(ArticleFilterType::SORT_CREATED, [$newsTheme], 7, 0, [], [], [$ourType]));

        $ourArticles = $em->getRepository(Article::class)
            ->findAllByFilter(new ArticleFilterType(ArticleFilterType::SORT_UPDATED, [], 12, 0, [$ourType]));

        $notOurNotNews = $em->getRepository(Article::class)
            ->findAllByFilter(new ArticleFilterType(ArticleFilterType::SORT_UPDATED, [], 6, 0, [], [$newsTheme], [$ourType]));

        $businessPosts = $em->getRepository(Post::class)->findAllByFilter(new PostsFilterType(PostsFilterType::USERS_ACCESS_POST_HOMEPAGE, null, null, null, null, 4, 0));

        return $this->render('client/default/index.html.twig', [
            "homePage" => $homePage,
            "articles" => $route === "homepage_all_users" ? [] : $updatedArticles,
            "ourArticles" => $ourArticles,
            "notOurNotNews" => $notOurNotNews,
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

    /**
     * @Route("/main-page-search-obd2-form", name="main_page_search_obd2_form", options={"expose"=true})
     */
    public function mainPageOBD2SearchFormAction(Request $request)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $requestData = json_decode($request->getContent(), true);
        $byName = isset($requestData["by-name"]) && $requestData["by-name"];
        $byDesignation = isset($requestData["by-designation"]) && $requestData["by-designation"];

        /** @var Brand|null $brand */
        $brand = $em->getRepository(Brand::class)->findOneBy([
            $byName ? "name" : "url" => $requestData["brand"]
        ]);
        /** @var Model|null $model */
        $model = $em->getRepository(Model::class)->findOneBy([
            "brand" => $brand,
            $byName ? "name" : "url" => $requestData["model"],
        ]);
        /** @var TypeOBD2Error|null $type */
        $type = $em->getRepository(TypeOBD2Error::class)->findOneBy([
            $byDesignation ? "designation" : "url" => $requestData["type"]
        ]);
        /** @var CodeOBD2Error|null $code */
        $code = $em->getRepository(CodeOBD2Error::class)->findOneBy([
            "type" => $type,
            "code" => $requestData["code"],
        ]);

        if($brand && !$type){
            $redirectUrl = $this->generateUrl("obd2_forum_choice_type", ["urlBrand" => $brand->getUrl()]);
        }
        elseif(!$brand && $type && !$code){
            $redirectUrl = $this->generateUrl("show_obd2_error_catalog_choice_code", ["urlType" => $type->getUrl()]);
        }
        elseif (!$brand && $type && $code){
            $redirectUrl = $this->generateUrl("show_obd2_error_catalog_choice_transcript",
                [
                    "urlType" => $type->getUrl(),
                    "urlCode" => $code->getUrl()
                ]
            );
        }
        elseif ($brand && $type && !$code && $model){
            $redirectUrl = $this->generateUrl("obd2_forum_choice_code",
                [
                    "urlType" => $type->getUrl(),
                    "urlBrand" => $brand->getUrl()
                ]
            );
        }
        elseif ($brand && $type && $code && !$model){
            $redirectUrl = $this->generateUrl("obd2_forum_choice_model",
                [
                    "urlType" => $type->getUrl(),
                    "urlBrand" => $brand->getUrl(),
                    "urlCode" => $code->getUrl(),
                ]
            );
        }
        elseif ($brand && $type && $code && $model){
            $redirectUrl = $this->generateUrl("obd2_forum_final_page",
                [
                    "urlType" => $type->getUrl(),
                    "urlBrand" => $brand->getUrl(),
                    "urlCode" => $code->getUrl(),
                    "urlModel" => $model->getUrl(),
                ]
            );
        }
        else{
            $redirectUrl = $this->generateUrl("show_obd2_error_catalog_choice_type");
        }

        return new JsonResponse(["redirectUrl" => $redirectUrl]);
    }

    /**
     * @Route("/article-header-slider", name="show_article_header_slider")
     */
    public function showArticleHeaderSliderAction(Request $request)
    {
        $updatedArticles = $this->getDoctrine()->getManager()->getRepository(Article::class)
            ->findAllByFilter(new ArticleFilterType(ArticleFilterType::SORT_UPDATED, [], 12, 0));

        return $this->render('client/default/parts/article-top-carousel.html.twig', [
            "articles" => $request->get('_route') === "homepage_interactiv_all_users" ? [] : $updatedArticles
        ]);
    }
}