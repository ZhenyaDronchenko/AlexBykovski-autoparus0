<?php

namespace App\Admin;

use App\Entity\City;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
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
        $formMapper->add('text', CKEditorType::class, ['label' => '[TEXTCITY]']);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('name', 'text', ['label' => 'name', 'sortable' => false]);
    }

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);

        $query->select('c')
            ->from(City::class, 'c')
            ->orderBy("c.name", "ASC");

        return $query;
    }
}