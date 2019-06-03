<?php

namespace App\Admin\User;

use App\Entity\User;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class BuyerAdmin extends AbstractAdmin
{
    protected $maxPerPage = 192;

    protected $baseRouteName = 'admin_app_buyer';
    protected $baseRoutePattern = 'buyer';

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name', TextType::class, ['label' => 'Имя/Nick', 'sortable' => false])
            ->addIdentifier('email', TextType::class, ['label' => 'e-mail', 'sortable' => false])
            ->addIdentifier('phone', TextType::class, ['label' => 'Телефон', 'sortable' => false])
            ->addIdentifier('city', TextType::class, ['label' => 'Город', 'sortable' => false])
            ->addIdentifier('brands', null, ['label' => "Марка", 'template' => 'admin/user/user_cars_brand.html.twig', 'sortable' => false, 'mapped' => false])
            ->addIdentifier('models', null, ['label' => "Модель", 'template' => 'admin/user/user_cars_model.html.twig', 'sortable' => false, 'mapped' => false])
            ->addIdentifier('isEnabled', 'boolean', ['label' => 'Актив', 'sortable' => false])
            ->addIdentifier('isHelper', 'boolean', ['label' => "helper", 'sortable' => false])
            ->addIdentifier('toPersonOffice', null, ['label' => false, 'template' => 'admin/user/link_to_user_office.html.twig', 'sortable' => false, 'mapped' => false])
            ->addIdentifier('isCopywriter', 'boolean', ['label' => "Копирайтер", 'template' => 'admin/user/role/is_copy_writer.html.twig', 'sortable' => false])
        ;
    }

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);

        $query
            ->where($query->getRootAlias().'.roles LIKE :role')
            ->setParameter('role', '%' . User::ROLE_BUYER . '%')
            ->orderBy($query->getRootAlias().'.createdAt', 'DESC');

        return $query;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['list']);
    }
}