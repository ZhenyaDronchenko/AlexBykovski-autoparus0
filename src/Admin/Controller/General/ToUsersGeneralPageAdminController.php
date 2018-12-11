<?php

namespace App\Admin\Controller\General;

use App\Entity\General\ToUsersGeneralPage;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ToUsersGeneralPageAdminController extends CRUDController
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

        $objectId = $this->getDoctrine()->getRepository(ToUsersGeneralPage::class)->findAll()[0]->getId();

        $request = $this->getRequest();
        $request->attributes->set('id', $objectId);

        return $this->editAction();

    }
}