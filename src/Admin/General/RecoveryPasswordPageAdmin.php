<?php

namespace App\Admin\General;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RecoveryPasswordPageAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('title', TextType::class, ['label' => 'Title']);
        $formMapper->add('description', TextType::class, ['label' => 'Description']);
        $formMapper->add('headline', TextType::class, ['label' => 'Заголовок']);
        $formMapper->add('textBottom', CKEditorType::class, ['label' => 'Текст внизу страницы']);
        $formMapper->add('textModal', CKEditorType::class, ['label' => 'Текст в модальной окне']);
        $formMapper->add('returnButtonText', TextType::class, ['label' => 'Текст универсальной кнопки (в модальном окне)']);
        $formMapper->add('returnButtonLink', TextType::class, ['label' => 'Ссылка универсальной кнопки (в модальном окне)']);
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