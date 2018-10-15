<?php

namespace App\Admin;

use App\Entity\DefaultImage;
use App\Helper\AdminHelper;
use App\Upload\FileUpload;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;

class DefaultImageAdmin extends AbstractAdmin
{
    protected $uploader = null;

    private $helper;

    public function __construct(
        string $code,
        string $class,
        string $baseControllerName,
        FileUpload $uploader,
        $uploadDirectory
    )
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->uploader = $uploader;

        $this->uploader->setFolder(FileUpload::DEFAULT_IMAGE);
        $this->helper = new AdminHelper($uploadDirectory);
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        /** @var DefaultImage $defaultImage */
        $defaultImage = $this->getSubject();
        $helpLogo = $defaultImage->getImage() ? $this->helper->getImagesHelp([$defaultImage->getImage()]) : "";

        $formMapper->add('description', TextType::class, [
            'label' => 'Название изображения',
            'required' => true,
        ]);
        $formMapper->add(
            'imageFile',
            FileType::class,
            ['label' => 'Изображение', 'required' => !$defaultImage->getImage(), 'mapped' => false],
            ["help" => $helpLogo]);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('id', 'integer', ['label' => '№', 'sortable' => false]);
        $listMapper->addIdentifier('description', 'text', ['label' => 'Название изображения', 'sortable' => false]);
        $listMapper->addIdentifier('image', 'text', [
            'label' => 'Адрес изображения',
            'sortable' => false,
            'template' => 'admin/default-image/full-link.html.twig'
        ]);
        $listMapper->addIdentifier('isExist', 'boolean', [
            'label' => false,
            'sortable' => false,
            'template' => 'admin/default-image/is_exist.html.twig'
        ]);
    }

    public function prePersist($defaultImage)
    {
        $this->uploadFiles($this->getForm(), $defaultImage);
    }

    public function preUpdate($defaultImage)
    {
        $this->uploadFiles($this->getForm(), $defaultImage);
    }

    protected function uploadFiles(Form $form, DefaultImage $defaultImage){
        $image = $form->get('imageFile')->getData();

        if($image){
            $uploadPath = $defaultImage->getImage() ?: null;

            $path = $this->uploader->upload($image, null, $uploadPath);

            $defaultImage->setImage($path);
        }
    }

    public function getBatchActions()
    {
        $actions = parent::getBatchActions();

        unset($actions["delete"]);

        return $actions;
    }
}