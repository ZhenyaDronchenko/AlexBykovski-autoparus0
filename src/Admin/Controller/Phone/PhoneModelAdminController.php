<?php

namespace App\Admin\Controller\Phone;

use App\Entity\Phone\PhoneModel;
use Doctrine\ORM\EntityManagerInterface;
use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class PhoneModelAdminController extends CRUDController
{
    public function batchActionSetActive(ProxyQueryInterface $selectedModelQuery)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $selectedModels = $selectedModelQuery->execute();

        /** @var PhoneModel $model */
        foreach ($selectedModels as $model){
            $model->setActive(true);
        }

        $em->flush();

        $this->get('session')->getFlashBag()->add('sonata_flash_success',  sprintf('Выбранные модели теперь активны'));

        return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }

    public function batchActionSetInActive(ProxyQueryInterface $selectedModelQuery)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $selectedModels = $selectedModelQuery->execute();

        /** @var PhoneModel $model */
        foreach ($selectedModels as $model){
            $model->setActive(false);
        }

        $em->flush();

        $this->get('session')->getFlashBag()->add('sonata_flash_success',  sprintf('Выбранные модели теперь неактивны'));

        return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }

    public function batchActionSetPopular(ProxyQueryInterface $selectedModelQuery)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $selectedModels = $selectedModelQuery->execute();

        /** @var PhoneModel $model */
        foreach ($selectedModels as $model){
            $model->setIsPopular(true);
        }

        $em->flush();

        $this->get('session')->getFlashBag()->add('sonata_flash_success',  sprintf('Выбранные модели теперь популярны'));

        return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }

    public function batchActionSetUnPopular(ProxyQueryInterface $selectedModelQuery)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $selectedModels = $selectedModelQuery->execute();

        /** @var PhoneModel $model */
        foreach ($selectedModels as $model){
            $model->setIsPopular(false);
        }

        $em->flush();

        $this->get('session')->getFlashBag()->add('sonata_flash_success',  sprintf('Выбранные модели теперь не популярны'));

        return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }
}