<?php

namespace App\Controller;

use App\Entity\Client\GalleryPhoto;
use App\Entity\General\MainPage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function showHomePageAction(Request $request)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $homePage = $em->getRepository(MainPage::class)->findAll()[0];

        return $this->render('client/default/index.html.twig', [
            "homePage" => $homePage,
            "posts" => $em->getRepository(GalleryPhoto::class)->findAllByCreatedAt()
        ]);
    }
}