<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\Catalog\SparePart\CatalogSparePartChoiceBrand;
use App\Entity\Catalog\SparePart\CatalogSparePartChoiceSparePart;
use App\Entity\SparePart;
use App\Transformer\VariableTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/zapchasti")
 */
class SparePartCatalogController extends Controller
{
    /**
     * @Route("/", name="show_spare_part_catalog_choice_spare_part")
     */
    public function showCatalogChoiceSparePartAction(Request $request)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $allSpareParts = $em->getRepository(SparePart::class)->findBy(["active" => true], ["name" => "ASC"]);

        $popularSpareParts = array_filter($allSpareParts, function(SparePart $sparePart){
            return $sparePart->isPopular();
        });

        return $this->render('client/catalog/spare-part/choice-spare-part.html.twig', [
            'allSpareParts' => $allSpareParts,
            'popularSpareParts' => $popularSpareParts,
            'page' => $em->getRepository(CatalogSparePartChoiceSparePart::class)->findAll()[0]
        ]);
    }

    /**
     * @Route("/{url}", name="show_spare_part_catalog_choice_brand")
     */
    public function showCatalogChoiceBrandAction(Request $request, $url, VariableTransformer $transformer)
    {
        $em = $this->getDoctrine()->getManager();
        $sparePart = $em->getRepository(SparePart::class)->findOneBy(["url" => $url]);

        if(!$sparePart){
            return $this->redirect($request->headers->get('referer'));
        }

        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $allBrands = $em->getRepository(Brand::class)->findBy(["active" => true], ["name" => "ASC"]);

        $popularBrands = array_filter($allBrands, function(Brand $brand){
            return $brand->isPopular();
        });

        $page = $em->getRepository(CatalogSparePartChoiceBrand::class)->findAll()[0];

        return $this->render('client/catalog/spare-part/choice-brand.html.twig', [
            'allBrands' => $allBrands,
            'popularBrands' => $popularBrands,
            'page' => $transformer->transformPage($page, [$sparePart])
        ]);
    }
}