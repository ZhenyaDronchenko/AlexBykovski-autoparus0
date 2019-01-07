<?php

namespace App\Admin\General;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ToUsersGeneralPageAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('title', TextType::class, ['label' => 'title']);
        $formMapper->add('description', TextType::class, ['label' => 'Description']);
        $formMapper->add('headline1', TextType::class, ['label' => 'Заголовок 1']);
        $formMapper->add('text1', CKEditorType::class, ['label' => 'Текст 1']);
        $formMapper->add('text2', CKEditorType::class, ['label' => 'Текст 2']);
        $formMapper->add('returnButtonText', TextType::class, ['label' => 'Надпись на универсальной кнопке']);
        $formMapper->add('returnButtonLink', TextType::class, ['label' => 'Адрес направления универсальной кнопки']);
        $formMapper->add('minskUrl', TextType::class, ['label' => 'URL для иконки Минск']);
        $formMapper->add('brestUrl', TextType::class, ['label' => 'URL для иконки Брест']);
        $formMapper->add('vitebskUrl', TextType::class, ['label' => 'URL для иконки Витебск']);
        $formMapper->add('gomelUrl', TextType::class, ['label' => 'URL для иконки Гомель']);
        $formMapper->add('grodnoUrl', TextType::class, ['label' => 'URL для иконки Гродно']);
        $formMapper->add('mogilevUrl', TextType::class, ['label' => 'URL для иконки Могилев']);
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