<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\Model;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class AdminController extends Controller
{
    /**
     * @Route("/admin-remove-brand-logo/{id}", name="admin_remove_brand_logo")
     *
     * @ParamConverter("brand", class="App:Brand", options={"id" = "id"})
     *
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function removeBrandLogoAction(Request $request, Brand $brand)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $brand->setLogo(null);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/admin-remove-model-logo/{id}", name="admin_remove_model_logo")
     *
     * @ParamConverter("model", class="App:Model", options={"id" = "id"})
     *
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function removeModelLogoAction(Request $request, Model $model)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $model->setLogo(null);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }

}