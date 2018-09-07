<?php

namespace App\Admin;

use App\Entity\SparePart;
use App\Helper\AdminHelper;
use App\Upload\FileUpload;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\Routing\RouterInterface;

class SparePartAdmin extends AbstractAdmin
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

        $this->uploader->setFolder(FileUpload::SPARE_PART);
        $this->helper = new AdminHelper($uploadDirectory);

        $this->router = $router;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $isEditAction = $this->isCurrentRoute('edit');
        /** @var SparePart $sparePart */
        $sparePart = $this->getSubject();
        $helpLogo = $isEditAction && $sparePart->getLogo() ? $this->helper->getImagesHelp([$sparePart->getLogo()]) : "";

        if($sparePart->getLogo()){
            $helpLogo .= $this->addLinkRemoveLogo($sparePart);
        }

        $formMapper->add('name', TextType::class, ['label' => 'Название запчасти [ZAP]']);
        $formMapper->add('url', TextType::class, [
            'label' => 'URL города [URLZAP]',
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
        $formMapper->add(
            'imageFile',
            FileType::class,
            ['label' => 'Логотип', 'required' => !$sparePart->getLogo(), 'mapped' => false],
            ["help" => $helpLogo]);

        $formMapper->add('text', CKEditorType::class, ['label' => '[TEXTZAP]']);
        $formMapper->add('active', CheckboxType::class, ['label' => 'Активная', 'required' => false]);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('name', 'text', ['label' => 'name', 'sortable' => false]);
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

        $query->select('b')
            ->from(SparePart::class, 'b')
            ->orderBy("b.name", "ASC");

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
}