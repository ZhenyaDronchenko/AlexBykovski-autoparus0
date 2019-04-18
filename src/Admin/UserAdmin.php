<?php

namespace App\Admin;

use App\Entity\User;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserAdmin extends AbstractAdmin
{
    protected $maxPerPage = 192;

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name', TextType::class, ['label' => 'Имя/Nick', 'sortable' => false])
            ->addIdentifier('email', TextType::class, ['label' => 'e-mail', 'sortable' => false])
            ->addIdentifier('phone', TextType::class, ['label' => 'Телефон', 'sortable' => false])
            ->addIdentifier('city', TextType::class, ['label' => 'Город', 'sortable' => false])
            ->addIdentifier('cars', null, ['label' => "Марка/Модель", 'template' => 'admin/user/user_cars.html.twig', 'sortable' => false])
            ->addIdentifier('isEnabled', 'boolean', ['label' => 'Актив', 'sortable' => false])
            ->addIdentifier('isSeller', null, ['label' => "Продавец", 'template' => 'admin/user/is_seller.html.twig', 'sortable' => false])
            ->addIdentifier('isServiceStation', 'boolean', ['label' => "СТО", 'sortable' => false])
            ->addIdentifier('isHelper', 'boolean', ['label' => "helper", 'sortable' => false])
            ->addIdentifier('isNews', 'boolean', ['label' => "Новости", 'sortable' => false])
            ->addIdentifier('toPersonOffice', null, ['label' => false, 'template' => 'admin/user/link_to_user_office.html.twig', 'sortable' => false, 'mapped' => false])
            ->addIdentifier('sellerCompany.unp', TextType::class, ['label' => 'УНП', 'sortable' => false])
            ->addIdentifier('sellerCompany.companyName', TextType::class, ['label' => 'Название организации', 'sortable' => false])
        ;
    }

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);

        $query
        ->where($query->getRootAlias().'.roles NOT LIKE :adminRole')
        ->setParameter('adminRole', '%' . User::ROLE_ADMIN . '%');

        return $query;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['list']);
    }
}