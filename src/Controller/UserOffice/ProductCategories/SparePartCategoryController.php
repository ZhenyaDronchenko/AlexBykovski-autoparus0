<?php

namespace App\Controller\UserOffice\ProductCategories;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/user-office/product-categories/spare-part")
 *
 * @Security("has_role('ROLE_CLIENT')")
 */
class SparePartCategoryController extends Controller
{
    /**
     * @Route("/", name="user_profile_product_categories_spare_part")
     */
    public function showProductCategoriesSparePartAction(Request $request)
    {
        return $this->render('client/user-office/seller-services/product-categories/spare-part-category.html.twig', []);
    }

    /**
     * @Route("/add-general-advert", name="user_profile_product_categories_spare_part_add_general_advert")
     */
    public function addGeneralAdvertSparePartAction(Request $request)
    {
        return $this->render('client/user-office/seller-services/product-categories/spare-part/add-general-advert.html.twig', []);
    }
}