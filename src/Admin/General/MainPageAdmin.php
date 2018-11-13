<?php

namespace App\Admin\General;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MainPageAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('title', TextType::class, ['label' => 'Title']);
        $formMapper->add('description', TextType::class, ['label' => 'Description']);
        $formMapper->add('text', CKEditorType::class, ['label' => 'Описание']);
        $formMapper->add('middleText', TextType::class, ['label' => 'Текст ссылки']);
        $formMapper->add('middleLink', TextType::class, ['label' => 'Ссылка ссылки']);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('id', 'text', ['label' => 'ID', 'sortable' => false])
            ->add('_action', null, [
                'actions' => [
                    'edit' => [],
                ]
            ]);
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['list', 'edit']);
    }
}