<?php

namespace App\Admin\Phone;

use App\Entity\Phone\PhoneSparePart;
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

class PhoneSparePartAdmin extends AbstractAdmin
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

        $this->uploader->setFolder(FileUpload::PHONE_SPARE_PART);
        $this->helper = new AdminHelper($uploadDirectory);

        $this->router = $router;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $isEditAction = $this->isCurrentRoute('edit');
        /** @var PhoneSparePart $sparePart */
        $sparePart = $this->getSubject();
        $helpLogo = $isEditAction && $sparePart->getLogo() ? $this->helper->getImagesHelp([$sparePart->getLogo()]) : "";

        if($sparePart->getLogo()){
            $helpLogo .= $this->addLinkRemoveLogo($sparePart);
        }

        $formMapper->add('name', TextType::class, ['label' => 'Название запчасти [TELZAP]']);
        $formMapper->add('url', TextType::class, [
            'label' => 'URL запчасти [URLTELZAP]',
            'attr' => [
                'style' => 'text-transform: lowercase'
            ]
        ]);
        $formMapper->add('nameAccusative', TextType::class, ['label' => 'Запчасть в падеже (кого, что) [VINTELZAP]']);
        $formMapper->add('nameGenitive', TextType::class, ['label' => 'Запчасть в падеже (кого, чего) [RODTELZAP]']);
        $formMapper->add('alternativeName1', TextType::class, ['label' => 'Альтернативное название запчасти 1 [TELZAP1]']);
        $formMapper->add('alternativeName2', TextType::class, ['label' => 'Альтернативное название запчасти 2 [TELZAP2]']);
        $formMapper->add('popular', CheckboxType::class, ['label' => 'Популярная', 'required' => false]);

        $formMapper->add(
            'imageFile',
            FileType::class,
            ['label' => 'Логотип', 'required' => !$sparePart->getLogo(), 'mapped' => false],
            ["help" => $helpLogo]);

        $formMapper->add('malfunction1', TextType::class, ['label' => 'Неисправность [TELMALFUNCTION1]']);
        $formMapper->add('malfunction2', TextType::class, ['label' => 'Неисправность [TELMALFUNCTION2]']);
        $formMapper->add('text1', CKEditorType::class, ['label' => '[TEXTTELZAP1]']);
        $formMapper->add('text2', CKEditorType::class, ['label' => '[TEXTTELZAP2]']);
        $formMapper->add('text3', CKEditorType::class, ['label' => '[TEXTTELZAP3]']);

        $formMapper->add('work', TextType::class, ['label' => 'Работа, связанная с запчастью [TELZAPRAB]']);
        $formMapper->add('actionWork', TextType::class, ['label' => 'Сделать работу с запчастью [TELZAPRABSDEL]']);

        $formMapper->add('active', CheckboxType::class, ['label' => 'Активная', 'required' => false]);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('name', 'text', ['label' => 'name', 'sortable' => false]);
        $listMapper->addIdentifier('popular', 'boolean', ['label' => 'Популяраня', 'sortable' => false]);
        $listMapper->addIdentifier('active', 'boolean', ['label' => 'Активная', 'sortable' => false]);
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

    protected function uploadFiles(Form $form, PhoneSparePart $sparePart){
        $image = $form->get('imageFile')->getData();

        if($image){
            $path = $this->uploader->upload($image);

            $sparePart->setLogo($path);
        }
    }

    protected function addLinkRemoveLogo(PhoneSparePart $sparePart)
    {
        $link = $this->router->generate("admin_remove_phone_spare_part_logo", ["id" => $sparePart->getId()]);

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