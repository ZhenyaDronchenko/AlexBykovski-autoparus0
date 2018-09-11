<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RobotsTxtAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'admin_app_robots_txt';
    protected $baseRoutePattern = 'robots-txt';

    private $pathToFile;

    public function __construct(
        string $code,
        string $class,
        string $baseControllerName,
        string $pathToFile
    )
    {
        parent::__construct($code, $class, $baseControllerName);

        $this->pathToFile = $pathToFile;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $data = file_get_contents($this->pathToFile);

        $formMapper->add('code', TextareaType::class, [
            'label' => 'Наполнение',
            'mapped' => false,
            'data' => $data,
            'attr' => [
                'rows' => 8
            ]
        ]);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->add('_action', null, [
                'actions' => [
                    'edit' => [],
                ]
            ]);
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['list', 'edit']);
    }

    public function preUpdate($object)
    {
        $data = $this->getForm()->get("code")->getData();

        file_put_contents($this->pathToFile, $data);
    }
}