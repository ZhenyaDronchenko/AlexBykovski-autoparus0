<?php

namespace App\Admin\Controller\Catalog\City;

use App\Entity\Catalog\City\CatalogCityChoiceBrand;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CatalogCityChoiceBrandAdminController extends CRUDController
{
    /**
     * return the Response object associated to the list action
     *
     * @throws AccessDeniedException
     *
     * @return Response
     */
    public function listAction()
    {
        if (false === $this->admin->isGranted('LIST')) {
            throw new AccessDeniedException();
        }

        $object = $this->getDoctrine()->getRepository(CatalogCityChoiceBrand::class)->findAll()[0];

        return $this->redirectTo($object);

    }
}