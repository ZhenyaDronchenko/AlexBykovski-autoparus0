<?php

namespace App\Controller;

use App\Entity\Catalog\SparePart\CatalogSparePartChoiceSparePart;
use App\Entity\SparePart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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

//        var_dump(count($popularSpareParts));
//        var_dump(count($allSpareParts));die;

        return $this->render('client/catalog/spare-part/choice-spare-part.html.twig', [
            'allSpareParts' => $allSpareParts,
            'popularSpareParts' => $popularSpareParts,
            'page' => $em->getRepository(CatalogSparePartChoiceSparePart::class)->findAll()[0]
        ]);
    }
}