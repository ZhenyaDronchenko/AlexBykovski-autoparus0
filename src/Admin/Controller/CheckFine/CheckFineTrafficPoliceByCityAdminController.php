<?php

namespace App\Admin\Controller\CheckFine;

use App\Entity\CheckFine\CheckFineTrafficPoliceByCity;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CheckFineTrafficPoliceByCityAdminController extends CRUDController
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

        $object = $this->getDoctrine()->getRepository(CheckFineTrafficPoliceByCity::class)->findAll()[0];

        return $this->redirectTo($object);

    }
}