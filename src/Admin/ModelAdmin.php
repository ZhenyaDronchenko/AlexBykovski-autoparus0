<?php

namespace App\Admin;


use App\Entity\Brand;
use App\Entity\DriveType;
use App\Entity\Engine;
use App\Entity\EngineType;
use App\Entity\GearBoxType;
use App\Entity\Model;
use App\Entity\ModelTechnicalData;
use App\Entity\VehicleCategory;
use App\Entity\VehicleType;
use App\Form\Type\EngineFormType;
use App\Form\Type\EngineWithoutCapacityFormType;
use App\Helper\AdminHelper;
use App\Upload\FileUpload;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Constraints\Count;

class ModelAdmin extends AbstractAdmin
{
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

        $this->uploader->setFolder(FileUpload::MODEL);
        $this->helper = new AdminHelper($uploadDirectory);

        $this->router = $router;
        $this->em = $em;
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
            'required' => true,
            'attr' => ['class' => 'model-year-from simple-width-field'],
            ]);
        $formMapper->add('technicalData.yearTo', IntegerType::class, [
            'label' => " ",
            'required' => true,
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
                'required' => true,
                'attr' => [
                    "class" => "checkboxes-in-one-line checkboxes-after-label"

                ],
                'constraints' => [
                    new Count([
                        'min' => 1,
                        'minMessage' => 'Выберите хотя бы 1'
                    ]),
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
                'required' => true,
                'attr' => [
                    "class" => "checkboxes-in-one-line checkboxes-after-label"
                ],
                'constraints' => [
                    new Count([
                        'min' => 1,
                        'minMessage' => 'Выберите хотя бы 1'
                    ]),
                ],
            ]
        );
        $formMapper->add('technicalData.gearBoxTypes',  EntityType::class,
            [
                'label' => 'Тип КПП',
                'class' => GearBoxType::class,
                'choice_label' => 'type',
                'multiple' => true,
                'expanded' => true,
                'required' => true,
                'attr' => [
                    "class" => "checkboxes-in-one-line checkboxes-after-label"
                ],
                'constraints' => [
                    new Count([
                        'min' => 1,
                        'minMessage' => 'Выберите хотя бы 1'
                    ]),
                ],
            ]
        );
        $formMapper->add('petrolEngines', CollectionType::class, [
            'entry_type' => EngineFormType::class,
            'allow_delete' => true,
            'allow_add' => true,
            'by_reference' => false,
            'mapped' => false,
            'required' => true,
            'label' => "бензин",
            'attr' => [
                "class" => "engine-capacity-container"
            ],
            'data' => $model->getTechnicalData()->getEnginesByType(Engine::PETROL_TYPE),
        ]);
        $formMapper->add('dieselEngines', CollectionType::class, [
            'entry_type' => EngineFormType::class,
            'allow_delete' => true,
            'allow_add' => true,
            'by_reference' => false,
            'mapped' => false,
            'required' => true,
            'label' => "дизель",
            'attr' => [
                "class" => "engine-capacity-container"
            ],
            'data' => $model->getTechnicalData()->getEnginesByType(Engine::DIESEL_TYPE),
        ]);
        $formMapper->add('hybridEngines', CollectionType::class, [
            'entry_type' => EngineFormType::class,
            'allow_delete' => true,
            'allow_add' => true,
            'by_reference' => false,
            'mapped' => false,
            'required' => true,
            'label' => "гибрид",
            'attr' => [
                "class" => "engine-capacity-container"
            ],
            'data' => $model->getTechnicalData()->getEnginesByType(Engine::HYBRID_TYPE),
        ]);
        $formMapper->add('electricEngines', CollectionType::class, [
            'entry_type' => EngineWithoutCapacityFormType::class,
            'allow_delete' => true,
            'allow_add' => true,
            'by_reference' => false,
            'mapped' => false,
            'required' => true,
            'label' => "Электро",
            'attr' => [
                "class" => "engine-capacity-container electric-engine-capacity-container"
            ],
            'data' => $model->getTechnicalData()->getEnginesByType(Engine::ELECTRIC_TYPE),
        ]);
        $formMapper->add('technicalData.vehicleTypes',  EntityType::class,
            [
                'label' => 'Тип транспортного средства',
                'class' => VehicleType::class,
                'choice_label' => 'type',
                'multiple' => true,
                'expanded' => true,
                'required' => true,
                'attr' => [
                    "class" => "checkboxes-after-label"
                ],
                'label_attr' => [
                    'class' => "vehicle-type-label"
                ],
                'constraints' => [
                    new Count([
                        'min' => 1,
                        'minMessage' => 'Выберите хотя бы 1'
                    ]),
                ],
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

    public function prePersist($model)
    {
        $brand = $this->em->getRepository(Brand::class)->find($this->getRequest()->get("id"));
        $model->setBrand($brand);

        $this->uploadModelFiles($this->getForm(), $model);
        $this->changeAllEngines($this->getForm(), $model);
    }

    public function preUpdate($model)
    {
        $this->uploadModelFiles($this->getForm(), $model);
        $this->changeAllEngines($this->getForm(), $model);
    }

    public function createQuery($context = 'list')
    {
        $brand = $this->em->getRepository(Brand::class)->find($this->getRequest()->get("id"));
        $query = parent::createQuery($context);

        $query->select('m')
            ->from(Model::class, 'm')
            ->where('m.brand = :brand')
            ->setParameter('brand', $brand)
            ->orderBy("m.name", "ASC");

        return $query;
    }

    protected function uploadModelFiles(Form $form, Model $model){
        $image = $form->get('imageFile')->getData();

        if($image){
            $path = $this->uploader->upload($image);

            $model->setLogo($path);
        }
    }

    protected function addLinkRemoveLogo(Model $model)
    {
        $link = $this->router->generate("admin_remove_model_logo", ["id" => $model->getId()]);

        return "<a href='" . $link . "'>Удалить лого</a>";
    }

    protected function changeAllEngines(Form $form, Model $model)
    {
        $petrolEngines = $form->get('petrolEngines')->getData();
        $dieselEngines = $form->get('dieselEngines')->getData();
        $hybridEngines = $form->get('hybridEngines')->getData();
        $electricEngines = $form->get('electricEngines')->getData();

        $this->removeOldEngines($model->getTechnicalData());

        $this->changeEnginesByType($petrolEngines, Engine::PETROL_TYPE, $model->getTechnicalData());
        $this->changeEnginesByType($dieselEngines, Engine::DIESEL_TYPE, $model->getTechnicalData());
        $this->changeEnginesByType($hybridEngines, Engine::HYBRID_TYPE, $model->getTechnicalData());
        $this->changeEnginesByType($electricEngines, Engine::ELECTRIC_TYPE, $model->getTechnicalData());
    }

    protected function removeOldEngines(ModelTechnicalData $technicalData)
    {
        /** @var Engine $engine */
        foreach ($technicalData->getEngines() as $engine){
            $this->em->remove($engine);
        }

        $technicalData->setEngines(new ArrayCollection());

        $this->em->flush();
    }

    protected function changeEnginesByType(array $engines, $type, ModelTechnicalData $technicalData)
    {
        $collection = $technicalData->getEngines();

        /** @var Engine $engine */
        foreach ($engines as $engine){
            $engine->setType($type);
            $collection->add($engine);
        }

        $technicalData->setEngines($collection);

        $this->em->flush();
    }
}