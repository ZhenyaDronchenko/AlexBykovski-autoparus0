<?php

namespace App\Admin\Controller\Catalog\SparePart;

use App\Entity\Catalog\SparePart\CatalogSparePartChoiceModel;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CatalogSparePartChoiceModelAdminController extends CRUDController
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

        $object = $this->getDoctrine()->getRepository(CatalogSparePartChoiceModel::class)->findAll()[0];

        return $this->redirectTo($object);

    }
}