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
     * @Route("/{id}", name="show_product_view")
     *
     * @ParamConverter("advert", class="App\Entity\Advert\AutoSparePart\AutoSparePartSpecificAdvert", options={"id" = "id"})
     */
    public function showProductViewAction(Request $request, AutoSparePartSpecificAdvert $advert)
    {
        return $this->render('client/product/product-view.html.twig', []);
    }

    /**
     * @Route("/{id}/{urlCity}", name="show_product_city_view")
     *
     * @ParamConverter("advert", class="App\Entity\Advert\AutoSparePart\AutoSparePartSpecificAdvert", options={"id" = "id"})
     */
    public function showProductCityViewAction(Request $request, AutoSparePartSpecificAdvert $advert, $urlCity)
    {
        return $this->render('client/product/product-city-view.html.twig', []);
    }
}