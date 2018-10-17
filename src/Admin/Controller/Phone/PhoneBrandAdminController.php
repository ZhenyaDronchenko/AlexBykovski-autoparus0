<?php

namespace App\Admin\Controller\Phone;

use App\Entity\Phone\PhoneBrand;
use Doctrine\ORM\EntityManagerInterface;
use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class PhoneBrandAdminController extends CRUDController
{
    public function batchActionSetActive(ProxyQueryInterface $selectedModelQuery)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $selectedBrands = $selectedModelQuery->execute();

        /** @var PhoneBrand $brand */
        foreach ($selectedBrands as $brand){
            $brand->setActive(true);
        }

        $em->flush();

        $this->get('session')->getFlashBag()->add('sonata_flash_success',  sprintf('Выбранные марки теперь активны'));

        return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }

    public function batchActionSetInActive(ProxyQueryInterface $selectedModelQuery)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $selectedBrands = $selectedModelQuery->execute();

        /** @var PhoneBrand $brand */
        foreach ($selectedBrands as $brand){
            $brand->setActive(false);
        }

        $em->flush();

        $this->get('session')->getFlashBag()->add('sonata_flash_success',  sprintf('Выбранные марки теперь неактивны'));

        return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }

    public function batchActionSetPopular(ProxyQueryInterface $selectedModelQuery)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $selectedBrands = $selectedModelQuery->execute();

        /** @var PhoneBrand $brand */
        foreach ($selectedBrands as $brand){
            $brand->setPopular(true);
        }

        $em->flush();

        $this->get('session')->getFlashBag()->add('sonata_flash_success',  sprintf('Выбранные марки теперь популярны'));

        return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }

    public function batchActionSetUnPopular(ProxyQueryInterface $selectedModelQuery)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $selectedBrands = $selectedModelQuery->execute();

        /** @var PhoneBrand $brand */
        foreach ($selectedBrands as $brand){
            $brand->setPopular(false);
        }

        $em->flush();

        $this->get('session')->getFlashBag()->add('sonata_flash_success',  sprintf('Выбранные марки теперь не популярны'));

        return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }
}