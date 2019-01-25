<?php

namespace App\Admin\Controller\Catalog\OBD2Error;

use App\Entity\Catalog\OBD2Error\CatalogOBD2ErrorChoiceCode;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CatalogOBD2ErrorChoiceCodeAdminController extends CRUDController
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

        $object = $this->getDoctrine()->getRepository(CatalogOBD2ErrorChoiceCode::class)->findAll()[0];

        return $this->redirectTo($object);

    }
}