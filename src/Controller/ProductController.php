<?php

namespace App\Controller;

use App\Entity\Advert\AutoSparePart\AutoSparePartSpecificAdvert;
use App\Entity\Article\Article;
use App\Entity\Brand;
use App\Entity\City;
use App\Entity\General\NotFoundPage;
use App\Entity\Model;
use App\Entity\SparePart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/product")
 */
class ProductController extends Controller
{
    /**
     * @Route("/{urlBrand}/{urlModel}/{urlSP}/{urlCity}", name="show_product_general_view")
     */
    public function showProductGeneralPageAction(Request $request, $urlBrand, $urlModel, $urlSP, $urlCity)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $sparePart = $em->getRepository(SparePart::class)->findOneBy(["url" => $urlSP]);
        $brand = $em->getRepository(Brand::class)->findOneBy(["url" => $urlBrand]);
        $model = $em->getRepository(Model::class)->findOneBy(["url" => $urlModel]);
        $city = $em->getRepository(City::class)->findOneBy(["url" => $urlCity]);
        $articles = $em->getRepository(Article::class)->findBy([], ["createdAt" => "DESC"], 2);

        if(!($sparePart instanceof SparePart) || !($brand instanceof Brand) ||
            !($model instanceof Model) || !($city instanceof City)){
            throw new NotFoundHttpException(NotFoundPage::DEFAULT_MESSAGE);
        }

        return $this->render('client/product/product-general-page.html.twig', [
            "brand" => $brand,
            "model" => $model,
            "sparePart" => $sparePart ?: new SparePart(),
            "city" => $city,
            "articles" => $articles,
        ]);
    }

    /**
     * @Route("/{urlBrand}/{urlModel}/{urlSP}", name="show_product_general_view_brand_model_spare_part")
     */
    public function showProductGeneralPageBrandModelSparePartAction(Request $request, $urlBrand, $urlModel, $urlSP)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $sparePart = $em->getRepository(SparePart::class)->findOneBy(["url" => $urlSP]);
        $brand = $em->getRepository(Brand::class)->findOneBy(["url" => $urlBrand]);
        $model = $em->getRepository(Model::class)->findOneBy(["url" => $urlModel]);
        $articles = $em->getRepository(Article::class)->findBy([], ["createdAt" => "DESC"], 2, 2);

        if(!($sparePart instanceof SparePart) || !($brand instanceof Brand) ||
            !($model instanceof Model)){
            throw new NotFoundHttpException(NotFoundPage::DEFAULT_MESSAGE);
        }

        return $this->render('client/product/product-general-page-brand-model-spare_part.html.twig', [
            "brand" => $brand,
            "model" => $model,
            "sparePart" => $sparePart ?: new SparePart(),
            "articles" => $articles,
        ]);
    }

    /**
     * @Route("/specific/{urlBrand}/{urlModel}/{urlSP}/{urlCity}/{id}", name="show_product_city_view")
     *
     */
    public function showProductViewAction(Request $request, $id, $urlBrand, $urlModel, $urlSP, $urlCity)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $sparePart = $em->getRepository(SparePart::class)->findOneBy(["url" => $urlSP]);
        $brand = $em->getRepository(Brand::class)->findOneBy(["url" => $urlBrand]);
        $model = $em->getRepository(Model::class)->findOneBy(["url" => $urlModel]);
        $city = $em->getRepository(City::class)->findOneBy(["url" => $urlCity]);
        $advert = $em->getRepository(AutoSparePartSpecificAdvert::class)->find($id);
        $articles = $em->getRepository(Article::class)->findBy([], ["createdAt" => "DESC"], 2);

        if(!($sparePart instanceof SparePart) || !($brand instanceof Brand) ||
            !($model instanceof Model) || !($city instanceof  City) ||  !$id){
            throw new NotFoundHttpException(NotFoundPage::DEFAULT_MESSAGE);
        }

        if(!($advert instanceof AutoSparePartSpecificAdvert)){
            return $this->redirectToRoute("show_product_general_view", [
                "urlBrand" => $urlBrand,
                "urlModel" => $urlModel,
                "urlSP" => $urlSP,
                "urlCity" => $urlCity,
            ]);
        }

        return $this->render('client/product/product-view.html.twig', [
            "brand" => $brand,
            "model" => $model,
            "sparePart" => $sparePart,
            "city" => $city,
            "articles" => $articles,
            "advert" => $advert
        ]);
    }

    /**
     * @Route("/specific/{urlBrand}/{urlModel}/{urlSP}/{id}", name="show_product_view")
     *
     */
    public function showProductViewBrandModelSparePartAction(Request $request, $id, $urlBrand, $urlModel, $urlSP)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $sparePart = $em->getRepository(SparePart::class)->findOneBy(["url" => $urlSP]);
        $brand = $em->getRepository(Brand::class)->findOneBy(["url" => $urlBrand]);
        $model = $em->getRepository(Model::class)->findOneBy(["url" => $urlModel]);
        $advert = $em->getRepository(AutoSparePartSpecificAdvert::class)->find($id);
        $articles = $em->getRepository(Article::class)->findBy([], ["createdAt" => "DESC"], 2);

        if(!($sparePart instanceof SparePart) || !($brand instanceof Brand) ||
            !($model instanceof Model) || !$id){
            throw new NotFoundHttpException(NotFoundPage::DEFAULT_MESSAGE);
        }

        if(!($advert instanceof AutoSparePartSpecificAdvert)){
            return $this->redirectToRoute("show_product_general_view_brand_model_spare_part", [
                "urlBrand" => $urlBrand,
                "urlModel" => $urlModel,
                "urlSP" => $urlSP,
            ]);
        }

        return $this->render('client/product/product-view-brand-model-spare-part.html.twig', [
            "brand" => $brand,
            "model" => $model,
            "sparePart" => $sparePart,
            "articles" => $articles,
            "advert" => $advert
        ]);
    }
}