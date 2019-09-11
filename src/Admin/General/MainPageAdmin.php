<?php

namespace App\Admin\General;

use App\Entity\General\MainPage;
use App\Entity\General\MainPageAction;
use App\Entity\General\MainPageBanner;
use App\Form\Type\Admin\MainPageActionType;
use App\Form\Type\Admin\MainPageBannerFormType;
use App\Helper\AdminHelper;
use App\Upload\FileUpload;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
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

class MainPageAdmin extends AbstractAdmin
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var FileUpload */
    protected $uploader;

    /** @var AdminHelper */
    private $helper;

    public function __construct(
        string $code,
        string $class,
        string $baseControllerName,
        EntityManagerInterface $em,
        FileUpload $uploader,
        $uploadDirectory
    )
    {
        parent::__construct($code, $class, $baseControllerName);

        $this->uploader = $uploader;

        $this->uploader->setFolder(FileUpload::GENERAL_MAIN_PAGE);
        $this->helper = new AdminHelper($uploadDirectory);

        $this->em = $em;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $isEditAction = $this->isCurrentRoute('edit');
        /** @var MainPage $page */
        $page = $this->getSubject();
        $helpLogo = $isEditAction && $page->getLogo() ? $this->helper->getImagesHelp($this->helper->getMainPageImage($page)) : "";

        $formMapper->add('showVoiceSearch', CheckboxType::class, [
            'label' => 'Скрыть (не отображать) поле поиска в хедере',
            "required" => false
        ]);
        $formMapper->add('title', TextType::class, ['label' => 'Title']);
        $formMapper->add('description', TextType::class, ['label' => 'Description']);
        $formMapper->add('text', CKEditorType::class, ['label' => 'Описание']);
        $formMapper->add('middleText', TextType::class, ['label' => 'Текст ссылки']);
        $formMapper->add('middleLink', TextType::class, ['label' => 'Ссылка ссылки']);
        $formMapper->add('actions', CollectionType::class, [
            'entry_type' => MainPageActionType::class,
            'allow_delete' => true,
            'allow_add' => true,
            'mapped' => false,
            'required' => true,
            'label' => "Действия",
            'data' => $page->getActions()
        ]);
        $formMapper->add(
            'logoFile',
            FileType::class,
            ['label' => 'Логотип', 'required' => !$page->getLogo(), 'mapped' => false],
            ["help" => $helpLogo]);
        $formMapper->add('banners', CollectionType::class, [
            'entry_type' => MainPageBannerFormType::class,
            'allow_delete' => true,
            'allow_add' => true,
            'required' => false,
            'label' => "Баннеры",
            'data' => $page->getBanners(),
            'attr' => [
                "class" => "article-images-container",
                "data-image-width" => 180,
                "data-image-height" => 120,
            ],
        ]);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('id', 'text', ['label' => 'ID', 'sortable' => false])
            ->add('_action', null, [
                'actions' => [
                    'edit' => [],
                ]
            ]);
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['list', 'edit']);
    }

    public function preUpdate($page)
    {
        $this->updateActions($page);
        $this->uploadLogo($this->getForm(), $page);
        $this->uploadBanners($this->getForm(), $page);
    }

    protected function updateActions(MainPage $page){
        /** @var MainPageAction $action */
        foreach ($page->getActions() as $action){
            $action->setMainPage($page);
        }
    }

    protected function uploadLogo(Form $form, MainPage $page){
        $image = $form->get('logoFile')->getData();

        if($image){
            $path = $this->uploader->upload($image);

            $page->setLogo($path);
        }
    }

    private function uploadBanners(Form $form, MainPage $page)
    {
        foreach ($form->get('banners') as $bannerForm){
            $file = $bannerForm->get("filePath")->getData();
            /** @var MainPageBanner $banner */
            $banner = $bannerForm->getData();

            if($file){
                $path = $this->uploader->upload($file);

                $banner->setImage($path);
            }


            $banner->setMainPage($page);
        }
    }
}