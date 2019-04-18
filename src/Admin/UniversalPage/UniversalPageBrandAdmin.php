<?php

namespace App\Admin\UniversalPage;

use App\Entity\Image;
use App\Entity\UniversalPage\UniversalPage;
use App\Helper\AdminHelper;
use App\Upload\FileUpload;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\RouterInterface;

class UniversalPageBrandAdmin extends AbstractAdmin
{
    protected $maxPerPage = 192;

    protected $uploader;

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

        $this->uploader->setFolder(FileUpload::UNIVERSAL_PAGE_BRAND);
        $this->helper = new AdminHelper($uploadDirectory);

        $this->router = $router;
        $this->em = $em;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        /** @var UniversalPage $page */
        $page = $this->getSubject();
        $helpLogo = $this->helper->getImagesHelp($this->helper->getImagesData($page));

        $formMapper->add('title', TextType::class, ['label' => 'title']);
        $formMapper->add('description', TextType::class, ['label' => 'description']);
        $formMapper->add('headline1', TextType::class, ['label' => 'Заголовок 1']);
        $formMapper->add('text1', CKEditorType::class, ['label' => 'Текст 1']);
        $formMapper->add('text2', CKEditorType::class, ['label' => 'Текст 2']);
        $formMapper->add('returnButtonText', TextType::class, [
            'label' => 'Надпись на универсальной кнопке',
            'required' => false,
        ]);
        $formMapper->add('returnButtonLink', TextType::class, [
            'label' => 'Адрес направления универсальной кнопки',
            'required' => false,
        ]);
        $formMapper->add('lastBreadCrumb', TextType::class, ['label' => 'Надпись на последней крошке']);
        $formMapper->add(
            'images',
            FileType::class,
            ['label' => 'Изображения', 'required' => false, 'multiple' => true, 'mapped' => false],
            ["help" => $helpLogo]);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('id', TextType::class, ['label' => 'Название', 'sortable' => false]);
        $listMapper->addIdentifier('url', TextType::class, [
            'label' => 'Адрес',
            'sortable' => false,
            'template' => 'admin/universal-page/brand/show-url.html.twig',
            'route' => ['name' => 'show']
        ]);
        $listMapper->addIdentifier('headline1', TextType::class, ['label' => 'Заголовок', 'sortable' => false]);
        $listMapper->addIdentifier('copyButton', null, [
            'label' => false,
            'mapped' => false,
            'template' => 'admin/universal-page/brand/copy-link.html.twig',
        ]);
    }

    public function prePersist($page)
    {
        $this->uploadFiles($this->getForm(), $page);
    }

    public function preUpdate($page)
    {
        $this->uploadFiles($this->getForm(), $page);
    }

    public function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('export');
    }

    public function getBatchActions()
    {
        $actions = parent::getBatchActions();

        unset($actions["delete"]);

        return $actions;
    }

    protected function uploadFiles(Form $form, UniversalPage $page)
    {
        $images = $form->get('images')->getData();

        if($images){
            $this->removeOldImages($page);

            $newImages = $this->uploadNewImages($images);
            $page->setImages($newImages);
        }
    }

    protected function removeOldImages(UniversalPage $page)
    {
        /** @var Image $image */
        foreach ($page->getImages() as $image)
        {
            $this->em->remove($image);
        }
    }

    protected function uploadNewImages($files)
    {
        $images = new ArrayCollection();

        /** @var UploadedFile $file */
        foreach ($files as $file){
            $path = $this->uploader->upload($file);

            $image = new Image($path);
            $this->em->persist($image);

            $images->add($image);
        }

        return $images;
    }
}