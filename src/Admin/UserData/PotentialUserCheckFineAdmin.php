<?php

namespace App\Admin\UserData;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PotentialUserCheckFineAdmin extends AbstractAdmin
{
    protected $maxPerPage = 192;

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('lastName', TextType::class, ['label' => 'Фамилия', 'sortable' => false])
            ->addIdentifier('firstName', TextType::class, ['label' => 'Имя', 'sortable' => false])
            ->addIdentifier('patronymic', TextType::class, ['label' => 'Отчество', 'sortable' => false])
            ->addIdentifier('series', TextType::class, ['label' => 'Серия ТП', 'sortable' => false])
            ->addIdentifier('number', TextType::class, ['label' => 'Номер ТП', 'sortable' => false])
            ->addIdentifier('phone', TextType::class, ['label' => 'Телефон', 'sortable' => false])
            ->addIdentifier('email', TextType::class, ['label' => 'E-mail', 'sortable' => false])
        ;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['list']);
    }
}