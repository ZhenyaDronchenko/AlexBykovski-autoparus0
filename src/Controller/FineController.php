<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\CheckFine\CheckFineTrafficPolice;
use App\Entity\CheckFine\CheckFineTrafficPoliceByCity;
use App\Entity\City;
use App\Entity\General\MainPage;
use App\Entity\General\NotFoundPage;
use App\Entity\UserData\PotentialUserCheckFine;
use App\Form\Type\PotentialUserCheckFineType;
use App\Provider\CheckFine\CheckFineTrafficPoliceProvider;
use App\Provider\TitleProvider;
use App\Transformer\VariableTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class FineController extends Controller
{
    /**
     * @Route("/proverka-shtrafa", name="check_fine")
     */
    public function checkFineAction(Request $request, TitleProvider $titleProvider)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $brands = $em->getRepository(Brand::class)->findBy(["active" => true], ["name" => "ASC"]);
        $capital = $em->getRepository(City::class)->findOneBy(["type" => City::CAPITAL_TYPE]);
        $regionalCities = $em->getRepository(City::class)->findBy(
            [
                "type" => City::REGIONAL_CITY_TYPE,
                "active" => true,
            ],
            ["name" => "ASC"]);

        $othersCities = $em->getRepository(City::class)->findBy(
            [
                "type" => City::OTHERS_TYPE,
                "active" => true,
            ],
            ["name" => "ASC"]);

        $popularBrands = [];
        $activeBrands = [];

        /** @var Brand $brand */
        foreach ($brands as $brand){
            $activeBrands[] = $brand;

            if($brand->isPopular()){
                $popularBrands[] = $brand;
            }
        }

        return $this->render('client/check-fine/check-fine.html.twig', [
            "page" => $em->getRepository(CheckFineTrafficPolice::class)->findAll()[0],
            'homepageTitle' => $titleProvider->getSinglePageTitle(MainPage::class),
            'popularBrands' => $popularBrands,
            'activeBrands' => $activeBrands,
            'cities' => array_merge([$capital], $regionalCities, $othersCities),
            'form' => $this->createForm(PotentialUserCheckFineType::class, new PotentialUserCheckFine())
                ->createView(),
        ]);
    }

    /**
     * @Route("/proverka-shtrafa/{cityUrl}", name="check_fine_in_city")
     */
    public function checkFineInCityAction(
        Request $request,
        TitleProvider $titleProvider,
        VariableTransformer $transformer,
        $cityUrl
    )
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $brands = $em->getRepository(Brand::class)->findBy(["active" => true], ["name" => "ASC"]);

        $city = $em->getRepository(City::class)->findOneBy(["url" => $cityUrl]);

        if(!($city instanceof City)){
            throw new NotFoundHttpException(NotFoundPage::DEFAULT_MESSAGE);
        }

        $popularBrands = [];
        $activeBrands = [];

        /** @var Brand $brand */
        foreach ($brands as $brand){
            $activeBrands[] = $brand;

            if($brand->isPopular()){
                $popularBrands[] = $brand;
            }
        }

        $page = $em->getRepository(CheckFineTrafficPoliceByCity::class)->findAll()[0];
        $cityChoiceTitle = $titleProvider->getSinglePageTitle(CheckFineTrafficPolice::class);

        return $this->render('client/check-fine/check-fine-by-city.html.twig', [
            "page" => $transformer->transformPage($page, [$city]),
            'homepageTitle' => $titleProvider->getSinglePageTitle(MainPage::class),
            'cityChoiceTitle' => $transformer->transformPage($cityChoiceTitle, [$city]),
            'popularBrands' => $popularBrands,
            'activeBrands' => $activeBrands,
            'city' => $city,
            'form' => $this->createForm(PotentialUserCheckFineType::class, new PotentialUserCheckFine())
                ->createView(),
        ]);
    }

    /**
     * @Route("/ajax/check-fine-traffic-policy", name="check_fine_traffic_policy_ajax")
     */
    public function ajaxCheckFineTrafficPolicyAction(Request $request, CheckFineTrafficPoliceProvider $provider)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $potentialUser = new PotentialUserCheckFine();
        $potentialUser->setUser($this->getUser());

        $form = $this->createForm(PotentialUserCheckFineType::class, $potentialUser);

        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()){
            $resultChecking = $provider->provideFineData($potentialUser);
            $potentialUser->setFineResult($resultChecking);

            $em->persist($potentialUser);
            $em->flush();

            return new JsonResponse([
                "success" => true,
                "checkResult" => $resultChecking,
            ]);
        }

        return $this->render('client/check-fine/part/check-fine-form.html.twig', [
            "form" => $form->createView(),
        ]);
    }
}