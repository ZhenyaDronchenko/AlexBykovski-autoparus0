<?php

namespace App\Admin\Controller\Catalog\OBD2Error;

use App\Entity\Catalog\OBD2Error\CatalogOBD2ErrorChoiceType;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CatalogOBD2ErrorChoiceTypeAdminController extends CRUDController
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

        $object = $this->getDoctrine()->getRepository(CatalogOBD2ErrorChoiceType::class)->findAll()[0];

        return $this->redirectTo($object);

    }
}