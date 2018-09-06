<?php

namespace App\Admin;


use App\Entity\DriveType;
use App\Entity\EngineType;
use App\Entity\GearBoxType;
use App\Entity\Model;
use App\Entity\VehicleCategory;
use App\Entity\VehicleType;
use App\Form\Type\EngineFormType;
use App\Helper\AdminHelper;
use App\Upload\FileUpload;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\RouterInterface;

class ModelAdmin extends BrandAdmin
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
        parent::__construct($code, $class, $baseControllerName, $uploader, $uploadDirectory, $router);
        $this->uploader = $uploader;

        $this->uploader->setFolder(FileUpload::MODEL);
        $this->helper = new AdminHelper($uploadDirectory);

        $this->router = $router;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $isEditAction = $this->isCurrentRoute('edit');
        /** @var Model $model */
        $model = $this->getSubject();
        $helpLogo = $isEditAction && $model->getLogo() ? $this->helper->getImagesHelp([$model->getLogo()]) : "";

        if($model->getLogo()){
            $helpLogo .= $this->addLinkRemoveLogo($model);
        }

        $formMapper->add('name', TextType::class, [
            'label' => 'Название модели [MODEL]',
            'required' => true,
            'attr' => [
                'style' => 'text-transform: uppercase'
            ]
        ]);
        $formMapper->add('url', TextType::class, [
            'label' => 'URL модели [URLMODEL]',
            'required' => true,
            'attr' => [
                'style' => 'text-transform: lowercase'
            ]
        ]);
        $formMapper->add('modelEn', TextType::class, ['label' => 'Модель на Английском [ENMODEL]', 'required' => true]);
        $formMapper->add('modelRu', TextType::class, ['label' => 'Модель на Русском [RUMODEL]', 'required' => true]);

        $formMapper->add('technicalData.yearFrom', IntegerType::class, [
            'label' => 'Годы выпуска:',
            'required' => false,
            'attr' => ['class' => 'model-year-from simple-width-field'],
            ]);
        $formMapper->add('technicalData.yearTo', IntegerType::class, [
            'label' => " ",
            'required' => false,
            'attr' => ['class' => 'model-year-to simple-width-field'],
        ]);
        $formMapper->add('imageFile', FileType::class, [
            'label' => 'Логотип',
            'required' => !$model->getLogo(),
            'mapped' => false],
            ["help" => $helpLogo]
        );
        $formMapper->add('popular', CheckboxType::class, ['label' => 'Популярная модель', 'required' => false]);
        $formMapper->add('technicalData.engineTypes',  EntityType::class,
            [
                'label' => 'Тип ДВС',
                'class' => EngineType::class,
                'choice_label' => 'type',
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    "class" => "checkboxes-in-one-line checkboxes-after-label"

                ],
            ]
        );
        $formMapper->add('technicalData.driveTypes',  EntityType::class,
            [
                'label' => 'Тип Привода',
                'class' => DriveType::class,
                'choice_label' => 'type',
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    "class" => "checkboxes-in-one-line checkboxes-after-label"
                ]
            ]
        );
        $formMapper->add('technicalData.gearBoxTypes',  EntityType::class,
            [
                'label' => 'Тип КПП',
                'class' => GearBoxType::class,
                'choice_label' => 'type',
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    "class" => "checkboxes-in-one-line checkboxes-after-label"
                ]
            ]
        );
        $formMapper->add('petrolEngines', CollectionType::class, [
            'entry_type' => EngineFormType::class,
            'allow_delete' => true,
            'allow_add' => true,
            'by_reference' => false,
            'mapped' => false,
            'label' => "бензин",
            'attr' => [
                "class" => "engine-capacity-container"
            ]
        ]);
        $formMapper->add('dieselEngines', CollectionType::class, [
            'entry_type' => EngineFormType::class,
            'allow_delete' => true,
            'allow_add' => true,
            'by_reference' => false,
            'mapped' => false,
            'label' => "дизель",
            'attr' => [
                "class" => "engine-capacity-container"
            ]
        ]);
        $formMapper->add('hybridEngines', CollectionType::class, [
            'entry_type' => EngineFormType::class,
            'allow_delete' => true,
            'allow_add' => true,
            'by_reference' => false,
            'mapped' => false,
            'label' => "гибрид",
            'attr' => [
                "class" => "engine-capacity-container"
            ]
        ]);
        $formMapper->add('electricEngines', CollectionType::class, [
            'entry_type' => EngineFormType::class,
            'allow_delete' => true,
            'allow_add' => true,
            'by_reference' => false,
            'mapped' => false,
            'label' => "Электро",
            'attr' => [
                "class" => "engine-capacity-container electric-engine-capacity-container"
            ]
        ]);
        $formMapper->add('technicalData.vehicleTypes',  EntityType::class,
            [
                'label' => 'Тип транспортного средства',
                'class' => VehicleType::class,
                'choice_label' => 'type',
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    "class" => "checkboxes-after-label"
                ]
            ]
        );
        $formMapper->add('technicalData.vehicleCategory',  EntityType::class,
            [
                'label' => 'Тип транспортного средства',
                'class' => VehicleCategory::class,
                'choice_label' => 'category',
                'multiple' => false,
                'expanded' => true,
            ]
        );
        $formMapper->add('text', CKEditorType::class, ['label' => '[TEXTMODEL]', 'required' => false]);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('name', TextType::class, ['label' => 'Название', 'sortable' => false]);
    }
}