<?php

namespace App\Controller;
use App\Entity\Brand;
use App\Entity\CatalogBrandChoiceBrand;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/brand-catalog")
 */
class BrandCatalogController extends Controller
{
    /**
     * @Route("/", name="show_brand_catalog_choice_brand")
     */
    public function showCatalogAction(Request $request)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $allBrands = $em->getRepository(Brand::class)->findBy(["active" => true], ["name" => "ASC"]);

        $popularBrands = array_filter($allBrands, function(Brand $brand){
            return $brand->isPopular();
        });

        return $this->render('client/catalog/brand/choice-brand.html.twig', [
            'allBrands' => $allBrands,
            'popularBrands' => $popularBrands,
            'page' => $em->getRepository(CatalogBrandChoiceBrand::class)->findAll()[0]
        ]);
    }
}