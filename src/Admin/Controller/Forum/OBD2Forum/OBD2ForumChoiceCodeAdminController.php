<?php

namespace App\Admin\Controller\Forum\OBD2Forum;

use App\Entity\Forum\OBD2Forum\OBD2ForumChoiceCode;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class OBD2ForumChoiceCodeAdminController extends CRUDController
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

        $object = $this->getDoctrine()->getRepository(OBD2ForumChoiceCode::class)->findAll()[0];

        return $this->redirectTo($object);

    }
}