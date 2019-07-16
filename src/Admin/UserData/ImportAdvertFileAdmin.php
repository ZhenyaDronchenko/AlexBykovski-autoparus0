<?php

namespace App\Admin\UserData;

use Doctrine\ORM\QueryBuilder;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ImportAdvertFileAdmin extends AbstractAdmin
{
    protected $datagridValues = [
        '_sort_order' => 'DESC',
        '_sort_by' => 'id',
    ];

    protected $maxPerPage = 192;

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id', TextType::class, ['label' => '#'])
            ->addIdentifier('email', null, ['label' => 'Пользователь', 'template' => 'admin/user-data/import-advert-file/user-email.html.twig', 'sortable' => false])
            ->addIdentifier('updatedAt', 'datetime', [
                'label' => 'Последняя загрузка',
                'sortable' => false,
                'format' => 'Y-m-d H:i',
                ])
            ->addIdentifier('path', null, ['label' => 'Файл', 'template' => 'admin/user-data/import-advert-file/imported-file.html.twig', 'sortable' => false])
            ->addIdentifier('dataValues', null, ['label' => 'Данные', 'template' => 'admin/user-data/import-advert-file/imported-count.html.twig', 'sortable' => false])
            ->addIdentifier('actionButtons', null, ['label' => false, 'template' => 'admin/user-data/import-advert-file/action-buttons.html.twig', 'sortable' => false])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('email', 'doctrine_orm_callback', [
                'callback'   => [$this, 'getByEmailFilter'],
            ])
        ;
    }

    public function getByEmailFilter(ProxyQuery $queryBuilder, $alias, $field, $value)
    {
        if (!$value['value']) {
            return false;
        }

        $queryBuilder
            ->leftJoin(sprintf('%s.sellerAdvertDetail', $alias), 'sad')
            ->leftJoin('sad.sellerData', 'sd')
            ->leftJoin('sd.client', 'cl')
            ->where("cl.email = :email")
            ->setParameter("email", $value['value']);

        return true;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['list']);
    }
}