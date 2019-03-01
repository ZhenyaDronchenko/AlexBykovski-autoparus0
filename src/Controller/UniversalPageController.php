<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/page", name="universal_page")
 */
class UniversalPageController extends Controller
{
    /**
     * @Route("/00001/{url}", name="universal_page_brand")
     */
    public function showToUsersPageAction(Request $request)
    {
        return $this->render('client/universal-page/brand.html.twig', [
        ]);
    }
}