<?php

namespace App\Admin\Phone;

use App\Entity\Phone\PhoneBrand;
use App\Entity\Phone\PhoneModel;
use App\Helper\AdminHelper;
use App\Upload\FileUpload;
use Doctrine\ORM\EntityManagerInterface;
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

class PhoneModelAdmin extends AbstractAdmin
{
    protected $maxPerPage = "All";
    protected $pagerType = "simple";

    protected $uploader = null;

    private $helper;

    /** @var RouterInterface */
    private $router;

    /** @var EntityManagerInterface */
    private $em;

    public function __construct(
        string $code,
        string $class,
        string $baseControllerName,
        FileUpload $uploader,
        $uploadDirectory,
        RouterInterface $router,
        EntityManagerInterface $em
    )
    {
        parent::__construct($code, $class, $baseControllerName);

        $this->uploader = $uploader;

        $this->uploader->setFolder(FileUpload::PHONE_MODEL);
        $this->helper = new AdminHelper($uploadDirectory);

        $this->router = $router;
        $this->em = $em;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $isEditAction = $this->isCurrentRoute('edit');
        /** @var PhoneModel $model */
        $model = $this->getSubject();
        $helpLogo = $isEditAction && $model->getLogo() ? $this->helper->getImagesHelp([$model->getLogo()]) : "";

        if($model->getLogo()){
            $helpLogo .= $this->addLinkRemoveLogo($model);
        }

        $formMapper->add('name', TextType::class, [
            'label' => 'Модель телефона [TELMODEL]',
            'required' => true,
            'attr' => [
                'style' => 'text-transform: uppercase'
            ]
        ]);
        $formMapper->add('url', TextType::class, [
            'label' => 'URL модели [URLTELMODEL]',
            'required' => true,
            'attr' => [
                'style' => 'text-transform: lowercase'
            ]
        ]);
        $formMapper->add('modelEn', TextType::class, ['label' => 'Модель на Английском [ENTELMODEL]', 'required' => true]);
        $formMapper->add('modelRu', TextType::class, ['label' => 'Модель на Русском [RUTELMODEL]', 'required' => true]);
        $formMapper->add('popular', CheckboxType::class, ['label' => 'Популярная модель', 'required' => false]);

        $formMapper->add('imageFile', FileType::class, [
            'label' => 'Логотип',
            'required' => !$model->getLogo(),
            'mapped' => false],
            ["help" => $helpLogo]
        );

        $formMapper->add('text1', CKEditorType::class, ['label' => '[TEXTTELMODEL1]', 'required' => false]);
        $formMapper->add('text2', CKEditorType::class, ['label' => '[TEXTTELMODEL2]', 'required' => false]);
        $formMapper->add('active', CheckboxType::class, ['label' => 'Активная', 'required' => false]);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('name', TextType::class, ['label' => 'Название', 'sortable' => false]);
        $listMapper->addIdentifier('isActive', 'boolean', ['label' => 'Активная', 'sortable' => false]);
        $listMapper->addIdentifier('isPopular', 'boolean', ['label' => 'Популярная', 'sortable' => false]);
        $listMapper->addIdentifier('url', TextType::class, ['label' => 'URL', 'sortable' => false]);
    }

    public function prePersist($model)
    {
        $brand = $this->em->getRepository(PhoneBrand::class)->find($this->getRequest()->get("id"));
        $model->setBrand($brand);

        $this->uploadModelFiles($this->getForm(), $model);
    }

    public function preUpdate($model)
    {
        $this->uploadModelFiles($this->getForm(), $model);
    }

    public function createQuery($context = 'list')
    {
        $brand = $this->em->getRepository(PhoneBrand::class)->find($this->getRequest()->get("id"));
        $query = parent::createQuery($context);

        $query->where($query->getRootAlias() . '.brand = :brand')
            ->setParameter('brand', $brand)
            ->orderBy($query->getRootAlias() . ".name", "ASC");

        return $query;
    }

    protected function uploadModelFiles(Form $form, PhoneModel $model){
        $image = $form->get('imageFile')->getData();

        if($image){
            $path = $this->uploader->upload($image);

            $model->setLogo($path);
        }
    }

    protected function addLinkRemoveLogo(PhoneModel $model)
    {
        $link = $this->router->generate("admin_remove_phone_model_logo", ["id" => $model->getId()]);

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