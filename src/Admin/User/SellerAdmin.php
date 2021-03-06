<?php

namespace App\Admin\User;

use App\Entity\User;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SellerAdmin extends AbstractAdmin
{
    protected $maxPerPage = 192;

    protected $baseRouteName = 'admin_app_seller';
    protected $baseRoutePattern = 'seller';

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('idUser', TextType::class, ['label' => 'ID', 'sortable' => false, 'mapped' => false, 'template' => 'admin/general/id_object.html.twig'])
            ->addIdentifier('createdAt', 'datetime', [
                'label' => 'Дата рег.',
                'sortable' => false,
                'format' => 'Y-m-d H:i',
            ])
            ->addIdentifier('name', TextType::class, ['label' => 'Имя/Nick', 'sortable' => false])
            ->addIdentifier('email', TextType::class, ['label' => 'e-mail', 'sortable' => false])
            ->addIdentifier('phone', TextType::class, ['label' => 'Телефон', 'sortable' => false])
            ->addIdentifier('city', TextType::class, ['label' => 'Город', 'template' => 'admin/user/city-show.html.twig', 'sortable' => false, 'mapped' => false])
            ->addIdentifier('isEnabled', 'boolean', ['label' => 'Актив', 'sortable' => false])
            ->addIdentifier('sellerData.sellerCompany.isService', 'boolean', ['label' => "СТО", 'template' => 'admin/user/is_service_seller.html.twig', 'sortable' => false])
            ->addIdentifier('sellerData.sellerCompany.isSparePartSeller', 'boolean', ['label' => "Товары", 'template' => 'admin/user/is_product_seller.html.twig', 'sortable' => false])
            ->addIdentifier('sellerData.sellerCompany.isNews', 'boolean', ['label' => "Новости", 'template' => 'admin/user/is_news_seller.html.twig', 'sortable' => false])
            ->addIdentifier('sellerData.sellerCompany.isAutoSeller', 'boolean', ['label' => "Авто", 'template' => 'admin/user/is_auto_seller.html.twig', 'sortable' => false])
            ->addIdentifier('sellerData.sellerCompany.isTourism', 'boolean', ['label' => "Туры", 'template' => 'admin/user/is_tourism.html.twig', 'sortable' => false])
            ->addIdentifier('isHelper', 'boolean', ['label' => "helper", 'sortable' => false])
            ->addIdentifier('toPersonOffice', null, ['label' => false, 'template' => 'admin/user/link_to_user_office.html.twig', 'sortable' => false, 'mapped' => false])
            ->addIdentifier('sellerData.sellerCompany.unp', TextType::class, ['label' => 'УНП', 'sortable' => false])
            ->addIdentifier('sellerData.sellerCompany.companyName', TextType::class, ['label' => 'Название организации', 'sortable' => false])
            ->addIdentifier('isShowPostsHomepage', 'boolean', ['label' => "VIP", 'template' => 'admin/user/role/is_show_posts_homepage.html.twig', 'sortable' => false])
        ;
    }

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);

        $query
            ->where($query->getRootAlias().'.roles LIKE :role')
            ->setParameter('role', '%' . User::ROLE_SELLER . '%')
            ->orderBy($query->getRootAlias().'.createdAt', 'DESC');

        return $query;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['list']);
    }
}