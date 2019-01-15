<?php

namespace App\Admin\UserData;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserEngineAdmin extends AbstractAdmin
{
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('model', null, ['label' => 'Марка/Модель', 'template' => 'admin/user-data/user-engine-model.html.twig', 'sortable' => false])
            ->addIdentifier('type', TextType::class, ['label' => 'Тип', 'sortable' => false])
            ->addIdentifier('capacity', TextType::class, ['label' => 'Объём', 'sortable' => false])
            ->addIdentifier('name', TextType::class, ['label' => 'Марка двигателя', 'sortable' => false])
            ->addIdentifier('approve', null, ['label' => false, 'template' => 'admin/user-data/agree-button.html.twig', 'sortable' => false])
            ->addIdentifier('reject', null, ['label' => false, 'template' => 'admin/user-data/reject-button.html.twig', 'sortable' => false])
        ;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['list']);
    }
}