<?php

namespace App\Admin\SEO;

use App\Entity\SEO\SiteMap;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Sonata\AdminBundle\Route\RouteCollection;

class SiteMapAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('type', ChoiceType::class, [
            'label' => false,
            'expanded' => true,
            'choices' => SiteMap::ADMIN_CHOICES,
        ]);
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['list', 'edit']);
    }
}