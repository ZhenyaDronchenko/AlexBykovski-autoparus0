<?php

namespace App\Admin\General;

use Doctrine\ORM\EntityManagerInterface;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class LoginPageAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('title', TextType::class, ['label' => 'Title']);
        $formMapper->add('description', TextType::class, ['label' => 'Description']);
        $formMapper->add('headline', TextType::class, ['label' => 'Заголовок']);
        $formMapper->add('textBottom', CKEditorType::class, ['label' => 'Текст внизу страницы']);
        $formMapper->add('textIncorrectName', CKEditorType::class, [
            'label' => 'Текст на первом модальном окне (неверный логин)',
        ]);
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