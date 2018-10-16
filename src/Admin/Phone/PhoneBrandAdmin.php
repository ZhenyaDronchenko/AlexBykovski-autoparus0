<?php

namespace App\Admin\Phone;

use App\Entity\Phone\PhoneBrand;
use App\Helper\AdminHelper;
use App\Upload\FileUpload;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\Routing\RouterInterface;

class PhoneBrandAdmin extends AbstractAdmin
{
    protected $maxPerPage = "All";
    protected $pagerType = "simple";

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

        $this->uploader->setFolder(FileUpload::PHONE_BRAND);
        $this->helper = new AdminHelper($uploadDirectory);

        $this->router = $router;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $isEditAction = $this->isCurrentRoute('edit');
        /** @var PhoneBrand $brand */
        $brand = $this->getSubject();
        $helpLogo = $isEditAction && $brand->getLogo() ? $this->helper->getImagesHelp([$brand->getLogo()]) : "";

        if($brand->getLogo()){
            $helpLogo .= $this->addLinkRemoveLogo($brand);
        }

        $formMapper->add('name', TextType::class, [
            'label' => 'Марка телефона [TELBRAND]',
            'required' => true,
            'attr' => [
                'style' => 'text-transform: uppercase'
            ]
        ]);
        $formMapper->add('url', TextType::class, [
            'label' => 'URL марки телефона [URLTELBRAND]',
            'required' => true,
            'attr' => [
                'style' => 'text-transform: lowercase'
            ]
        ]);
        $formMapper->add('brandEn', TextType::class, ['label' => 'Марка на Английском [ENTELBRAND]', 'required' => true]);
        $formMapper->add('brandRu', TextType::class, ['label' => 'Марка на Русском [RUTELBRAND]', 'required' => true]);
        $formMapper->add('popular', CheckboxType::class, ['label' => 'Популярная', 'required' => false]);
        $formMapper->add(
            'imageFile',
            FileType::class,
            ['label' => 'Логотип', 'required' => !$brand->getLogo(), 'mapped' => false],
            ["help" => $helpLogo]);
        $formMapper->add('text', CKEditorType::class, ['label' => '[TEXTTELBRAND]', 'required' => false]);
        $formMapper->add('active', CheckboxType::class, ['label' => 'Активная', 'required' => false]);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('name', TextType::class, ['label' => 'Название', 'sortable' => false]);
        $listMapper->addIdentifier('url', TextType::class, ['label' => 'URL', 'sortable' => false]);
        $listMapper->addIdentifier('active', 'boolean', [
            'label' => 'Активная', 'sortable' => false
        ]);
        $listMapper->addIdentifier('popular', 'boolean', [
            'label' => 'Популярная', 'sortable' => false
        ]);

        $listMapper->addIdentifier('modelsCount', 'string', [
            'label' => 'Модели',
            'sortable' => false,
            'template' => 'admin/phone/brand/models_count.html.twig'
        ]);
    }

    public function prePersist($brand)
    {
        $this->uploadFiles($this->getForm(), $brand);
    }

    public function preUpdate($brand)
    {
        $this->uploadFiles($this->getForm(), $brand);
    }

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);

        $query->orderBy($query->getRootAlias().'.name', 'ASC');

        return $query;
    }

    protected function uploadFiles(Form $form, PhoneBrand $brand){
        $image = $form->get('imageFile')->getData();

        if($image){
            $path = $this->uploader->upload($image);

            $brand->setLogo($path);
        }
    }

    protected function addLinkRemoveLogo(PhoneBrand $brand)
    {
        $link = $this->router->generate("admin_remove_phone_brand_logo", ["id" => $brand->getId()]);

        return "<a href='" . $link . "'>Удалить лого</a>";
    }

    public function configureRoutes(RouteCollection $collection) {
        $collection->remove('export');
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

        $actions['set_popular'] = [
            'label'            => 'Сделать популярными',
            'ask_confirmation' => true // If true, a confirmation will be asked before performing the action
        ];

        $actions['set_unpopular'] = [
            'label'            => 'Снять популярность',
            'ask_confirmation' => true // If true, a confirmation will be asked before performing the action
        ];

        return $actions;
    }
}