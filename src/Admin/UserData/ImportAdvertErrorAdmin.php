<?php

namespace App\Admin\UserData;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ImportAdvertErrorAdmin extends AbstractAdmin
{
    protected $datagridValues = [
        '_sort_order' => 'DESC',
        '_sort_by' => 'id',
    ];

    protected $maxPerPage = 64;

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id', TextType::class, ['label' => '#'])
            ->addIdentifier('issueField', TextType::class, ['label' => 'Проблемное поле', 'sortable' => false])
            ->addIdentifier('fieldValue', TextType::class, ['label' => 'Значение', 'sortable' => false])
            ->addIdentifier('issue', TextType::class, ['label' => 'Проблема', 'sortable' => false])
            ->addIdentifier('lineData', null, ['label' => 'Данные', 'template' => 'admin/user-data/import-advert-error/line-data.html.twig', 'sortable' => false])
            ->addIdentifier('approve', null, ['label' => false, 'template' => 'admin/user-data/import-advert-error/submit-button.html.twig', 'sortable' => false])
            ->addIdentifier('remove', null, ['label' => false, 'template' => 'admin/user-data/import-advert-error/remove-button.html.twig', 'sortable' => false])
        ;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['list']);
    }
}