<?php

namespace App\Admin\UserData;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserOBD2ErrorCodeAdmin extends AbstractAdmin
{
    protected $maxPerPage = 192;

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('type.designation', TextType::class, ['label' => 'Тип', 'sortable' => false])
            ->addIdentifier('code', TextType::class, ['label' => 'Код', 'sortable' => false])
            ->addIdentifier('counter', TextType::class, ['label' => 'Счётчик', 'sortable' => false])
            ->addIdentifier('approve', null, ['label' => false, 'template' => 'admin/user-data/user-obd2-error-code/agree-button.html.twig', 'sortable' => false])
            ->addIdentifier('reject', null, ['label' => false, 'template' => 'admin/user-data/user-obd2-error-code/reject-button.html.twig', 'sortable' => false])
        ;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['list']);
    }
}