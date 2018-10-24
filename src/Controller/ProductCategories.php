<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/user-office/product-categories")
 *
 * @Security("has_role('ROLE_CLIENT')")
 */
class ProductCategories extends Controller
{
    /**
     * @Route("/", name="user_profile_product_categories")
     */
    public function showProductCategoriesAction(Request $request)
    {
        return $this->render('client/user-office/seller-services/product-categories.html.twig', []);
    }

    /**
     * @Route("/spare-part", name="user_profile_product_categories_spare_part")
     */
    public function showProductCategoriesSparePartAction(Request $request)
    {
        return $this->render('client/user-office/seller-services/product-categories/spare-part-category.html.twig', []);
    }
}