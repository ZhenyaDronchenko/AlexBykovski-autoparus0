<?php

namespace App\Admin\Catalog\Turbo;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CatalogTurboChoiceCityAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('title', TextType::class, ['label' => 'title']);
        $formMapper->add('description', TextType::class, ['label' => 'description']);
        $formMapper->add('headline1', TextType::class, ['label' => 'Заголовок 1']);
        $formMapper->add('text1', CKEditorType::class, ['label' => 'Текст 1']);
        $formMapper->add('headline2', TextType::class, ['label' => 'Заголовок 2']);
        $formMapper->add('text2', CKEditorType::class, ['label' => 'Текст 2']);
        $formMapper->add('returnButtonText', TextType::class, ['label' => 'Надпись на универсальной кнопке']);
        $formMapper->add('returnButtonLink', TextType::class, ['label' => 'Адрес направления универсальной кнопки']);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
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