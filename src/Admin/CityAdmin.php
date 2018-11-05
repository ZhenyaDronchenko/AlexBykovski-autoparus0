<?php

namespace App\Admin;

use App\Entity\City;
use App\Helper\AdminHelper;
use App\Upload\FileUpload;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\Routing\RouterInterface;

class CityAdmin extends AbstractAdmin
{
    protected $uploader = null;

    private $helper;

    /** @var RouterInterface */
    private $router;

    public function __construct(
        string $code,
        string $class,
        string $baseControllerName,
        FileUpload $uploader,
        $uploadDirectory,
        RouterInterface $router
    )
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->uploader = $uploader;

        $this->uploader->setFolder(FileUpload::CITY);
        $this->helper = new AdminHelper($uploadDirectory);

        $this->router = $router;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $isEditAction = $this->isCurrentRoute('edit');
        /** @var City $city */
        $city = $this->getSubject();
        $helpLogo = $isEditAction && $city->getLogo() ? $this->helper->getImagesHelp([$city->getLogo()]) : "";

        if($city->getLogo()){
            $helpLogo .= $this->addLinkRemoveLogo($city);
        }

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
        $formMapper->add(
            'imageFile',
            FileType::class,
            ['label' => 'Логотип', 'required' => !$city->getLogo(), 'mapped' => false],
            ["help" => $helpLogo]);
        $formMapper->add('text', CKEditorType::class, ['label' => '[TEXTCITY]']);
        $formMapper->add('active', CheckboxType::class, ['label' => 'Активный', 'required' => false]);
        $formMapper->add('urlConnectBamper', TextType::class, ['label' => 'URL для коннекта с bamper.by', 'required' => false]);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('name', 'text', ['label' => 'Название', 'sortable' => false]);
        $listMapper->addIdentifier('url', 'text', ['label' => 'URL', 'sortable' => false]);
        $listMapper->addIdentifier('active', 'boolean', ['label' => 'Активный', 'sortable' => false]);
        $listMapper->addIdentifier('getTypeTranslate', 'text', ['label' => 'Тип', 'sortable' => false]);
    }

    public function prePersist($city)
    {
        $this->uploadFiles($this->getForm(), $city);
    }

    public function preUpdate($city)
    {
        $this->uploadFiles($this->getForm(), $city);
    }

    protected function uploadFiles(Form $form, City $city){
        $image = $form->get('imageFile')->getData();

        if($image){
            $path = $this->uploader->upload($image);

            $city->setLogo($path);
        }
    }

    protected function addLinkRemoveLogo(City $city)
    {
        $link = $this->router->generate("admin_remove_city_logo", ["id" => $city->getId()]);

        return "<a href='" . $link . "'>Удалить лого</a>";
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