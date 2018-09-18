<?php

namespace App\Admin\Controller;

use App\Entity\Brand;
use App\Entity\SparePart;
use Doctrine\ORM\EntityManagerInterface;
use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SparePartAdminController extends CRUDController
{
    public function batchActionSetActive(ProxyQueryInterface $selectedModelQuery)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $selectedSpareParts = $selectedModelQuery->execute();

        /** @var SparePart $sparePart */
        foreach ($selectedSpareParts as $sparePart){
            $sparePart->setActive(true);
        }

        $em->flush();

        $this->get('session')->getFlashBag()->add('sonata_flash_success',  sprintf('Выбранные запчасти теперь активны'));

        return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }

    public function batchActionSetInActive(ProxyQueryInterface $selectedModelQuery)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $selectedSpareParts = $selectedModelQuery->execute();

        /** @var SparePart $sparePart */
        foreach ($selectedSpareParts as $sparePart){
            $sparePart->setActive(false);
        }

        $em->flush();

        $this->get('session')->getFlashBag()->add('sonata_flash_success',  sprintf('Выбранные запчасти теперь неактивны'));

        return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }

    public function batchActionSetPopular(ProxyQueryInterface $selectedModelQuery)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $selectedSpareParts = $selectedModelQuery->execute();

        /** @var SparePart $sparePart */
        foreach ($selectedSpareParts as $sparePart){
            $sparePart->setPopular(true);
        }

        $em->flush();

        $this->get('session')->getFlashBag()->add('sonata_flash_success',  sprintf('Выбранные запчасти теперь популярны'));

        return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }

    public function batchActionSetUnPopular(ProxyQueryInterface $selectedModelQuery)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $selectedSpareParts = $selectedModelQuery->execute();

        /** @var SparePart $sparePart */
        foreach ($selectedSpareParts as $sparePart){
            $sparePart->setPopular(false);
        }

        $em->flush();

        $this->get('session')->getFlashBag()->add('sonata_flash_success',  sprintf('Выбранные запчасти теперь не популярны'));

        return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }
}