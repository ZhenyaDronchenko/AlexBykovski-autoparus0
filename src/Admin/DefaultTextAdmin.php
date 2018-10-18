<?php

namespace App\Admin;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class DefaultTextAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('description', TextType::class, ['label' => 'Название текста']);
        $formMapper->add('headline', TextType::class, ['label' => 'Заголовок']);
        $formMapper->add('text', CKEditorType::class, ['label' => 'Текст']);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('id', 'integer', ['label' => '№', 'sortable' => false]);
        $listMapper->addIdentifier('description', 'text', ['label' => 'Название текста', 'sortable' => false]);
        $listMapper->addIdentifier('headline', 'text', ['label' => 'Заголовок', 'sortable' => false]);
    }

    public function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('delete');
    }

    public function getBatchActions()
    {
        $actions = parent::getBatchActions();

        unset($actions["delete"]);

        return $actions;
    }
}