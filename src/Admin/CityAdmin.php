<?php

namespace App\Admin;

use App\Entity\City;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CityAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('name', TextType::class, ['label' => 'Название города [CITY]']);
        $formMapper->add('url', TextType::class, [
            'label' => 'URL города [URLCITY]',
            'attr' => [
                'style' => 'text-transform: lowercase'
            ]
        ]);
        $formMapper->add('prepositional', TextType::class, ['label' => 'Город в падеже [INCITY]']);
        $formMapper->add('type', ChoiceType::class, [
            'choices'  => City::$types,
            'expanded' => true,
        ]);
        $formMapper->add('active', CheckboxType::class, ['label' => 'Активный', 'required' => false]);
        $formMapper->add('text', CKEditorType::class, ['label' => '[TEXTCITY]']);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('name', 'text', ['label' => 'Название', 'sortable' => false]);
        $listMapper->addIdentifier('url', 'text', ['label' => 'URL', 'sortable' => false]);
        $listMapper->addIdentifier('active', 'boolean', ['label' => 'Активный', 'sortable' => false]);
        $listMapper->addIdentifier('getTypeTranslate', 'text', ['label' => 'Тип', 'sortable' => false]);
    }

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);

        $query->orderBy($query->getRootAlias().'.name', 'ASC');

        return $query;
    }

    public function getBatchActions()
    {
        $actions = parent::getBatchActions();

        unset($actions["delete"]);

        $actions['set_active'] = [
            'label'            => 'Сделать активными',
            'ask_confirmation' => true // If true, a confirmation will be asked before performing the action
        ];

        $actions['set_inactive'] = [
            'label'            => 'Сделать не активными',
            'ask_confirmation' => true // If true, a confirmation will be asked before performing the action
        ];

        $actions['set_capital_type'] = [
            'label'            => 'Сделать столицей',
            'ask_confirmation' => true // If true, a confirmation will be asked before performing the action
        ];

        $actions['set_regional_type'] = [
            'label'            => 'Сделать областным',
            'ask_confirmation' => true // If true, a confirmation will be asked before performing the action
        ];

        $actions['set_other_type'] = [
            'label'            => 'Сделать другим',
            'ask_confirmation' => true // If true, a confirmation will be asked before performing the action
        ];

        return $actions;
    }
}