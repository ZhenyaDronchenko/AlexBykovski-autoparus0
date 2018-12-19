<?php

namespace App\Admin\Controller\Catalog\Brand;

use App\Entity\Catalog\Brand\CatalogBrandChoiceFinalPage;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CatalogBrandChoiceFinalPageAdminController extends CRUDController
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

        $object = $this->getDoctrine()->getRepository(CatalogBrandChoiceFinalPage::class)->findAll()[0];

        return $this->redirectTo($object);

    }
}