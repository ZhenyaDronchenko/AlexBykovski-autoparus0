<?php

namespace App\Admin\Error;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TypeOBD2ErrorAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('type', TextType::class, ['label' => 'Название типа ошибки [TYPEOBD2]']);
        $formMapper->add('url', TextType::class, ['label' => 'URL типа ошибки [URLTYPEOBD2]']);
        $formMapper->add('designation', TextType::class, ['label' => 'Обозначение типа ошибки [LETTERTYPEOBD2]']);
        $formMapper->add('description', TextareaType::class, ['label' => 'Описание типа ошибки [TEXTTYPEOBD2]']);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('id', 'integer', ['label' => '#', 'sortable' => false]);
        $listMapper->addIdentifier('type', 'text', ['label' => 'Тип ошибки', 'sortable' => false]);
        $listMapper->addIdentifier('url', 'text', ['label' => 'URL', 'sortable' => false]);
        $listMapper->addIdentifier('description', 'text', ['label' => 'Описание типа ошибки', 'sortable' => false]);
        $listMapper->addIdentifier('codes', null, ['label' => 'Ошибки', 'template' => 'admin/error/codes_obd2_count.html.twig', 'sortable' => false]);
    }

    public function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('export');
        $collection->remove('delete');
        $collection->remove('create');
    }

    public function getBatchActions()
    {
        $actions = parent::getBatchActions();

        unset($actions["delete"]);

        return $actions;
    }
}