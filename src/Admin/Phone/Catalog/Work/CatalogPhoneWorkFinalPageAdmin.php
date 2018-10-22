<?php

namespace App\Admin\Phone\Catalog\Work;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CatalogPhoneWorkFinalPageAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('title', TextType::class, ['label' => 'title']);
        $formMapper->add('description', TextType::class, ['label' => 'description']);
        $formMapper->add('returnButtonText', TextType::class, ['label' => 'НАЗВАНИЕ ВОЗВРАТНОЙ КНОПКИ']);
        $formMapper->add('returnButtonLink', TextType::class, ['label' => 'АДРЕС НАПРАВЛЕНИЯ ВОЗВРАТНОЙ КНОПКИ']);
        $formMapper->add('text1', CKEditorType::class, ['label' => 'Заголовок 1 и текст к нему']);
        $formMapper->add('text2', CKEditorType::class, ['label' => 'Заголовок 2 и текст к нему']);
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