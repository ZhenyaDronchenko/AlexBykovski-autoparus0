<?php

namespace App\Controller\Phone;

use App\Entity\Phone\Catalog\Work\CatalogPhoneWorkChoicePhoneWork;
use App\Entity\Phone\PhoneSparePart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/phon_remont")
 */
class PhoneWorkCatalogController extends Controller
{
    /**
     * @Route("/", name="show_phone_work_catalog_choice_work")
     */
    public function showCatalogChoiceWorkAction(Request $request)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $allSpareParts = $em->getRepository(PhoneSparePart::class)->findBy(["active" => true], ["work" => "ASC"]);

        $popularSpareParts = array_filter($allSpareParts, function(PhoneSparePart $sparePart){
            return $sparePart->isPopular();
        });

        return $this->render('client/phone/catalog/work/choice-work.html.twig', [
            'allSpareParts' => $allSpareParts,
            'popularSpareParts' => $popularSpareParts,
            'page' => $em->getRepository(CatalogPhoneWorkChoicePhoneWork::class)->findAll()[0]
        ]);
    }

    /**
     * @Route("/{url}/", name="show_phone_work_catalog_choice_phone_brand")
     */
    public function showCatalogChoiceBrandAction(Request $request, $url)
    {
//        $em = $this->getDoctrine()->getManager();
//        $sparePart = $em->getRepository(SparePart::class)->findOneBy(["url" => $url]);
//
//        if(!($sparePart instanceof SparePart)){
//            return $this->redirect($request->headers->get('referer'));
//        }
//
//        /** @var EntityManagerInterface $em */
//        $em = $this->getDoctrine()->getManager();
//        $allBrands = $em->getRepository(Brand::class)->findBy(["active" => true], ["name" => "ASC"]);
//
//        $popularBrands = array_filter($allBrands, function(Brand $brand){
//            return $brand->isPopular();
//        });
//
//        $page = $em->getRepository(CatalogSparePartChoiceBrand::class)->findAll()[0];
//
//        return $this->render('client/catalog/spare-part/choice-brand.html.twig', [
//            'allBrands' => $allBrands,
//            'popularBrands' => $popularBrands,
//            'page' => $transformer->transformPage($page, [$sparePart]),
//            'sparePart' => $sparePart,
//        ]);
    }
}