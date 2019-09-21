<?php

namespace App\Controller;

use App\Entity\Article\Article;
use App\Entity\Article\ArticleTheme;
use App\Entity\Brand;
use App\Entity\City;
use App\Entity\Client\Client;
use App\Entity\Client\Post;
use App\Entity\Client\SellerCompany;
use App\Entity\Engine;
use App\Entity\EngineType;
use App\Entity\Model;
use App\Entity\SparePart;
use App\Entity\User;
use App\Type\ArticleFilterType;
use App\Type\PostsFilterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

/**
 * @Route("/search")
 */
class SearchController extends Controller
{
    const ALL_VARIANTS = "all_preload_variants";

    /**
     * @Route("/spare-part", name="search_spare_part_autocomplete")
     * @Route("/spare-part/ids", name="search_spare_part_ids_autocomplete")
     */
    public function searchSparePartAction(Request $request)
    {
        /** @var RouterInterface $router */
        $router = $this->get('router');
        $text = $request->query->get("text");

        if(!is_string($text) || strlen($text) < 1){
            return new JsonResponse([]);
        }

        $text = $text !== self::ALL_VARIANTS ? $text : "";

        $spareParts = $this->getDoctrine()->getRepository(SparePart::class)->searchByText($text);

        $parsedSpareParts = [];
        $routeReferrer = $request->get('_route');

        /** @var SparePart $sparePart */
        foreach ($spareParts as $sparePart){
            $sparePart = $sparePart->toSearchArray();

            if($routeReferrer === "search_spare_part_ids_autocomplete"){
                $sparePart["url"] = $sparePart["id"];
            }

            $parsedSpareParts[] = $sparePart;
        }

        return new JsonResponse($parsedSpareParts);
    }

    /**
     * @Route("/brand", name="search_brand_autocomplete")
     */
    public function searchBrandAction(Request $request)
    {
        $text = $request->query->get("text");

        if(!is_string($text) || strlen($text) < 1){
            return new JsonResponse([]);
        }

        $isAllVariants = $text === self::ALL_VARIANTS;

        $text = !$isAllVariants ? $text : "";

        $isRussianText = preg_match('/[А-Яа-яЁё]/u', $text);

        $brands = $this->getDoctrine()->getRepository(Brand::class)->searchByText($text, $isRussianText);

        $parsedBrands = [];

        /** @var Brand $brand */
        foreach ($brands as $brand){
            $parsedBrands[] = $brand->toSearchArray($isRussianText);

            if($isAllVariants){
                $parsedBrands[] = $brand->toSearchArray(!$isRussianText);
            }
        }

        return new JsonResponse($parsedBrands);
    }

    /**
     * @Route("/model/{urlBrand}", name="search_model_autocomplete")
     */
    public function searchModelAction(Request $request, $urlBrand)
    {
        $text = $request->query->get("text");
        $byName = $request->query->has("by-name");

        $brand = $this->getDoctrine()->getRepository(Brand::class)->findOneBy(
            [$byName ? "name" : "url" => $urlBrand]
        );

        if(!is_string($text) || strlen($text) < 1 || !($brand instanceof Brand)){
            return new JsonResponse([]);
        }

        $isAllVariants = $text === self::ALL_VARIANTS;

        $text = !$isAllVariants ? $text : "";
        //$isRussianText = preg_match('/[А-Яа-яЁё]/u', $text);

        $models = $this->getDoctrine()->getRepository(Model::class)->searchByText($text, $brand, false);

        $parsedModels = [];

        /** @var Model $model */
        foreach ($models as $model){
            $parsedModels[] = $model->toSearchArray(false);

//            if($isAllVariants){
//                $parsedModels[] = $model->toSearchArray(!$isRussianText);
//            }
        }

        return new JsonResponse($parsedModels);
    }

    /**
     * @Route("/year/{urlBrand}/{urlModel}", name="search_year_autocomplete")
     */
    public function searchYearAction(Request $request, $urlBrand, $urlModel)
    {
        $text = $request->query->get("text");
        $byName = $request->query->has("by-name");

        $brand = $this->getDoctrine()->getRepository(Brand::class)->findOneBy(
             [$byName ? "name" : "url" => $urlBrand]
        );
        $model = $this->getDoctrine()->getRepository(Model::class)->findOneBy([
            "brand" => $brand,
            $byName ? "name" : "url" => $urlModel
        ]);

        if(!is_string($text) || strlen($text) < 1 || !($model instanceof Model)){
            return new JsonResponse([]);
        }

        $isAllVariants = $text === self::ALL_VARIANTS;

        $text = !$isAllVariants ? $text : "";

        return new JsonResponse($model->yearsToSearchArray($text));
    }

    /**
     * @Route("/engine-type/{urlBrand}/{urlModel}", name="search_engine_type_autocomplete")
     */
    public function searchEngineTypeAction(Request $request, $urlBrand, $urlModel)
    {
        $text = $request->query->get("text");

        $brand = $this->getDoctrine()->getRepository(Brand::class)->findOneBy(["url" => $urlBrand]);
        $model = $this->getDoctrine()->getRepository(Model::class)->findOneBy([
            "brand" => $brand,
            "url" => $urlModel
        ]);

        if(!is_string($text) || strlen($text) < 1 || !($model instanceof Model)){
            return new JsonResponse([]);
        }

        $isAllVariants = $text === self::ALL_VARIANTS;

        $text = !$isAllVariants ? $text : "";

        return new JsonResponse($model->engineTypesToSearchArray($text));
    }

    /**
     * @Route("/engine-capacity/{urlBrand}/{urlModel}/{engineTypeUrl}", name="search_engine_capacity_autocomplete")
     */
    public function searchEngineCapacityAction(Request $request, $urlBrand, $urlModel, $engineTypeUrl)
    {
        $text = $request->query->get("text");

        $brand = $this->getDoctrine()->getRepository(Brand::class)->findOneBy(["url" => $urlBrand]);
        $model = $this->getDoctrine()->getRepository(Model::class)->findOneBy([
            "brand" => $brand,
            "url" => $urlModel
        ]);
        $engine = $this->getDoctrine()->getRepository(EngineType::class)->findOneBy(["url" => $engineTypeUrl]);

        if(!is_string($text) || strlen($text) < 1 || !($model instanceof Model) || !($engine instanceof EngineType)){
            return new JsonResponse([]);
        }

        $isAllVariants = $text === self::ALL_VARIANTS;

        $text = !$isAllVariants ? $text : "";

        return new JsonResponse($model->capacitiesToSearchArray($engine->getType(), $text));
    }

    /**
     * @Route("/vehicle-type/{urlBrand}/{urlModel}", name="search_vehicle_type_autocomplete")
     */
    public function searchVehicleTypeAction(Request $request, $urlBrand, $urlModel)
    {
        $text = $request->query->get("text");

        $brand = $this->getDoctrine()->getRepository(Brand::class)->findOneBy(["url" => $urlBrand]);
        $model = $this->getDoctrine()->getRepository(Model::class)->findOneBy([
            "brand" => $brand,
            "url" => $urlModel
        ]);

        if(!is_string($text) || strlen($text) < 1 || !($model instanceof Model)){
            return new JsonResponse([]);
        }

        $isAllVariants = $text === self::ALL_VARIANTS;

        $text = !$isAllVariants ? $text : "";

        return new JsonResponse($model->vehicleTypesToSearchArray($text));
    }


    /**
     * @Route("/posts", name="search_posts")
     */
    public function searchPostsAction(Request $request)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $requestData = json_decode($request->getContent(), true);
        $offset = isset($requestData["offset"]) ? $requestData["offset"] : null;
        $limit = isset($requestData["limit"]) ? $requestData["limit"] : null;
        $isAllLight = isset($requestData["isAllLight"]) ? $requestData["isAllLight"] : false;
        $type = isset($requestData["type"]) ? $requestData["type"] : null;

        $user = isset($requestData["userId"]) ? $em->getRepository(Client::class)->find($requestData["userId"]) : null;
        $allUsers = isset($requestData["allUsers"]) && $requestData["allUsers"];
        $notPremium = isset($requestData["notPremium"]) && $requestData["notPremium"];
        $brand = isset($requestData["urlBrand"]) ? $em->getRepository(Brand::class)->findOneBy(["url" => $requestData["urlBrand"]]) : null;
        $model = isset($requestData["urlModel"]) ? $em->getRepository(Model::class)->findOneBy(["url" => $requestData["urlModel"]]) : null;
        $city = isset($requestData["urlCity"]) ? $em->getRepository(City::class)->findOneBy(["url" => $requestData["urlCity"]]) : null;
        $activity = isset($requestData["urlActivity"]) && array_key_exists($requestData["urlActivity"], SellerCompany::$activities) ?
            SellerCompany::$activities[$requestData["urlActivity"]] : null;
        $users = $user ?: ($allUsers ? '' : PostsFilterType::USERS_ACCESS_POST_HOMEPAGE);
        $users = $notPremium ? null : $users;
        $notUserRoles = $notPremium ? User::ROLE_SHOW_POSTS_HOMEPAGE : null;
        $filter = new PostsFilterType($users, $brand, $model, $city, $activity, $limit, $offset, $notUserRoles, $type);

        if(!is_numeric($offset) || !is_numeric($limit)){
            return new JsonResponse([]);
        }

        $posts = $em->getRepository(Post::class)->findAllByFilter($filter);

        $parsedPosts = [];

        /** @var Post $post */
        foreach ($posts as $post){
            $parsedPost= $post->toSearchArray($isAllLight);

            if($parsedPost["city"]){
                $city = $em->getRepository(City::class)->findOneBy(["name" => $parsedPost["city"]]);
                $parsedPost["city"] = $city ? $city->getUrl() : null;
            }

            if($parsedPost["brand"]){
                $brand = $em->getRepository(Brand::class)->findOneBy(["name" => $parsedPost["brand"]]);
                $parsedPost["brand"] = $brand ? $brand->getUrl() : null;
            }

            if($parsedPost["model"]){
                $model = $em->getRepository(Model::class)->findOneBy(["name" => $parsedPost["model"]]);
                $parsedPost["model"] = $model ? $model->getUrl() : null;
            }

            $parsedPosts[] = $parsedPost;
        }

        return new JsonResponse($parsedPosts);
    }

    /**
     * @Route("/articles", name="search_articles")
     */
    public function searchArticlesAction(Request $request)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $requestData = json_decode($request->getContent(), true);
        $offset = isset($requestData["offset"]) ? $requestData["offset"] : null;
        $limit = isset($requestData["limit"]) ? $requestData["limit"] : null;
        $theme = isset($requestData["urlTheme"]) ? $em->getRepository(ArticleTheme::class)->findOneBy(["url" => $requestData["urlTheme"]]) : null;
        $themesSearch = $theme ? [$theme] : [];


        $filter = new ArticleFilterType(ArticleFilterType::SORT_UPDATED, $themesSearch, $limit, $offset);

        if(!is_numeric($offset) || !is_numeric($limit)){
            return new JsonResponse([]);
        }

        $articles = $em->getRepository(Article::class)->findAllByFilter($filter);

        $parsedArticles = [];

        foreach ($articles as $article){
            $parsedArticle = $article["object"]->toSearchArray();

            if(!$parsedArticle){
                continue;
            }

            $parsedArticles[] = $parsedArticle;
        }

        return new JsonResponse($parsedArticles);
    }
}