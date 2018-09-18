<?php

namespace App\Admin\Controller;

use App\Entity\City;
use Doctrine\ORM\EntityManagerInterface;
use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CityAdminController extends CRUDController
{
    public function batchActionSetActive(ProxyQueryInterface $selectedModelQuery)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $selectedCities = $selectedModelQuery->execute();

        /** @var City $city */
        foreach ($selectedCities as $city){
            $city->setActive(true);
        }

        $em->flush();

        $this->get('session')->getFlashBag()->add('sonata_flash_success',  sprintf('Выбранные города теперь активны'));

        return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }

    public function batchActionSetInActive(ProxyQueryInterface $selectedModelQuery)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $selectedCities = $selectedModelQuery->execute();

        /** @var City $city */
        foreach ($selectedCities as $city){
            $city->setActive(false);
        }

        $em->flush();

        $this->get('session')->getFlashBag()->add('sonata_flash_success',  sprintf('Выбранные города теперь неактивны'));

        return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }

    public function batchActionSetCapitalType(ProxyQueryInterface $selectedModelQuery)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $selectedCities = $selectedModelQuery->execute();

        /** @var City $city */
        foreach ($selectedCities as $city){
            $city->setType(City::CAPITAL_TYPE);
        }

        $em->flush();

        $this->get('session')->getFlashBag()->add('sonata_flash_success',  sprintf('Выбранные города стали столицами'));

        return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }

    public function batchActionSetRegionalType(ProxyQueryInterface $selectedModelQuery)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $selectedCities = $selectedModelQuery->execute();

        /** @var City $city */
        foreach ($selectedCities as $city){
            $city->setType(City::REGIONAL_CITY_TYPE);
        }

        $em->flush();

        $this->get('session')->getFlashBag()->add('sonata_flash_success',  sprintf('Выбранные города стали областными'));

        return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }

    public function batchActionSetOtherType(ProxyQueryInterface $selectedModelQuery)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $selectedCities = $selectedModelQuery->execute();

        /** @var City $city */
        foreach ($selectedCities as $city){
            $city->setType(City::OTHERS_TYPE);
        }

        $em->flush();

        $this->get('session')->getFlashBag()->add('sonata_flash_success',  sprintf('Выбранные города стали другими'));

        return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }
}