<?php

namespace App\Admin\User;

use App\Entity\User;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SellerAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'admin_app_seller';
    protected $baseRoutePattern = 'seller';

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name', TextType::class, ['label' => 'Имя/Nick', 'sortable' => false])
            ->addIdentifier('email', TextType::class, ['label' => 'e-mail', 'sortable' => false])
            ->addIdentifier('phone', TextType::class, ['label' => 'Телефон', 'sortable' => false])
            ->addIdentifier('city', TextType::class, ['label' => 'Город', 'sortable' => false])
            ->addIdentifier('isEnabled', 'boolean', ['label' => 'Актив', 'sortable' => false])
            ->addIdentifier('sellerData.sellerCompany.isService', 'boolean', ['label' => "СТО", 'template' => 'admin/user/is_service_seller.html.twig', 'sortable' => false])
            ->addIdentifier('isHelper', 'boolean', ['label' => "helper", 'sortable' => false])
            ->addIdentifier('sellerData.sellerCompany.isNews', 'boolean', ['label' => "Новости", 'template' => 'admin/user/is_news_seller.html.twig', 'sortable' => false])
            ->addIdentifier('toPersonOffice', null, ['label' => false, 'template' => 'admin/user/link_to_user_office.html.twig', 'sortable' => false, 'mapped' => false])
            ->addIdentifier('sellerData.sellerCompany.unp', TextType::class, ['label' => 'УНП', 'sortable' => false])
            ->addIdentifier('sellerData.sellerCompany.companyName', TextType::class, ['label' => 'Название организации', 'sortable' => false])
        ;
    }

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);

        $query
            ->where($query->getRootAlias().'.roles LIKE :role')
            ->setParameter('role', '%' . User::ROLE_SELLER . '%');

        return $query;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['list']);
    }
}