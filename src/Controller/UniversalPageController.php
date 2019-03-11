<?php

namespace App\Controller;

use App\Entity\General\MainPage;
use App\Entity\SparePart;
use App\Entity\UniversalPage\UniversalPageSparePart;
use App\Provider\UniversalPageProvider;
use App\Transformer\VariableTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class UniversalPageController extends Controller
{
    /**
     * @Route("/page-brand/{id}", name="universal_page_brand")
     * @Route("/page-brand/{id}/{urlBrand}", name="universal_page_brand_specific_brand")
     *
     * @ParamConverter("brand", class="App\Entity\UniversalPage\UniversalPageBrand", options={"id" = "id"})
     */
    public function showUniversalBrandPageAction(Request $request)
    {
        return $this->render('client/universal-page/brand.html.twig', [
        ]);
    }

    /**
     * @Route("/city/{url}", name="universal_page_city")
     */
    public function showUniversalCityPageAction(Request $request)
    {
        return $this->render('client/universal-page/city.html.twig', [
        ]);
    }


    /**
     * @Route("/page-zapchasti/{id}", name="universal_page_spare_part")
     * @Route("/page-zapchasti/{id}/{urlSp}", name="universal_page_brand_specific_spare_part")
     *
     * @ParamConverter("page", class="App\Entity\UniversalPage\UniversalPageSparePart", options={"id" = "id"})
     */
    public function showUniversalSparePartPageAction(
        Request $request,
        UniversalPageSparePart $page,
        UniversalPageProvider $universalPageProvider,
        VariableTransformer $transformer,
        $urlSp = null
    )
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $sparePart = $urlSp ? $em->getRepository(SparePart::class)->findOneBy(["url" => $urlSp]) : null;
        $transformerParameters = $sparePart ? [$sparePart] : [];

        return $this->render('client/universal-page/spare-part.html.twig', [
            "page" => $transformer->transformPage($page, $transformerParameters),
            "sparePart" => $sparePart,
            "titleHomepage" => $universalPageProvider->getMailPageTitle(),
            "brands" => $universalPageProvider->getBrands(),
        ]);
    }
}