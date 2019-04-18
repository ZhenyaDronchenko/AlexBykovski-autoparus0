<?php

namespace App\Admin;

use App\Entity\SparePart;
use App\Form\Type\SparePartConditionFormType;
use App\Helper\AdminHelper;
use App\Upload\FileUpload;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\Routing\RouterInterface;

class SparePartAdmin extends AbstractAdmin
{
    protected $maxPerPage = 192;

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

        $this->uploader->setFolder(FileUpload::SPARE_PART);
        $this->helper = new AdminHelper($uploadDirectory);

        $this->router = $router;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $isEditAction = $this->isCurrentRoute('edit');
        /** @var SparePart $sparePart */
        $sparePart = $this->getSubject();
        $helpLogo = $isEditAction && $sparePart->getLogo() ? $this->helper->getImagesHelp($this->helper->getImagesData($sparePart)) : "";

        if($sparePart->getLogo()){
            $helpLogo .= $this->addLinkRemoveLogo($sparePart);
        }

        $formMapper->add('name', TextType::class, ['label' => 'Название запчасти [ZAP]']);
        $formMapper->add('url', TextType::class, [
            'label' => 'URL запчасти [URLZAP]',
            'attr' => [
                'style' => 'text-transform: lowercase'
            ]
        ]);
        $formMapper->add('nameAccusative', TextType::class, ['label' => 'Запчасть в падеже (кого, что) [VINZAP]']);
        $formMapper->add('nameInstrumental', TextType::class, ['label' => 'Запчасть в падеже (кем, чем) [TVORZAP]']);
        $formMapper->add('nameGenitive', TextType::class, ['label' => 'Запчасть в падеже (кого, чего) [RODZAP]']);
        $formMapper->add('namePlural', TextType::class, ['label' => 'Запчасть во множ. числе [ZAPS]']);
        $formMapper->add('alternativeName1', TextType::class, ['label' => 'Альтернативное название запчасти 1 [ZAP1]']);
        $formMapper->add('alternativeName2', TextType::class, ['label' => 'Альтернативное название запчасти 2 [ZAP2]']);
        $formMapper->add('alternativeName3', TextType::class, ['label' => 'Альтернативное название запчасти 3 [ZAP3]']);
        $formMapper->add('alternativeName4', TextType::class, ['label' => 'Альтернативное название запчасти 4 [ZAP4]']);
        $formMapper->add('alternativeName5', TextType::class, ['label' => 'Альтернативное название запчасти 5 [ZAP5]']);
        $formMapper->add('popular', CheckboxType::class, ['label' => 'Популярная запчасть', 'required' => false]);
        $formMapper->add('conditions', CollectionType::class, [
            'label' => 'Состояния запчасти [ZAP_CONDITION]',
            'entry_type' => SparePartConditionFormType::class,
            'required' => false,
            'attr' => [
                "class" => "spare-part-conditions-container"
            ],
            'allow_add' => false
        ]);
        $formMapper->add(
            'imageFile',
            FileType::class,
            ['label' => 'Логотип', 'required' => !$sparePart->getLogo(), 'mapped' => false],
            ["help" => $helpLogo]);

        $formMapper->add('text', CKEditorType::class, ['label' => '[TEXTZAP]']);
        $formMapper->add('active', CheckboxType::class, ['label' => 'Активная', 'required' => false]);
        $formMapper->add('urlConnectBamper', TextType::class, ['label' => 'URL для коннекта с bamper.by', 'required' => false]);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('name', 'text', ['label' => 'name', 'sortable' => false]);
        $listMapper->addIdentifier('popular', 'boolean', ['label' => 'Популяраня', 'sortable' => false]);
        $listMapper->addIdentifier('active', 'boolean', ['label' => 'Активная', 'sortable' => false]);
        $listMapper->addIdentifier('isHasUsed', 'boolean', ['label' => 'БУ', 'sortable' => false]);
        $listMapper->addIdentifier('isHasNew', 'boolean', ['label' => 'Новая', 'sortable' => false]);
        $listMapper->addIdentifier('isHasRebuilt', 'boolean', ['label' => 'Восстановленная', 'sortable' => false]);
    }

    public function prePersist($sparePart)
    {
        $this->uploadFiles($this->getForm(), $sparePart);
    }

    public function preUpdate($sparePart)
    {
        $this->uploadFiles($this->getForm(), $sparePart);
    }

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);

        $query->orderBy($query->getRootAlias().'.name', 'ASC');

        return $query;
    }

    protected function uploadFiles(Form $form, SparePart $sparePart){
        $image = $form->get('imageFile')->getData();

        if($image){
            $path = $this->uploader->upload($image);

            $sparePart->setLogo($path);
        }
    }

    protected function addLinkRemoveLogo(SparePart $sparePart)
    {
        $link = $this->router->generate("admin_remove_spare_part_logo", ["id" => $sparePart->getId()]);

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