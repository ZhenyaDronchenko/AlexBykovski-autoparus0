<?php

namespace App\Admin;

use App\Entity\Article\Article;
use App\Entity\Article\ArticleImage;
use App\Entity\Article\ArticleTheme;
use App\Entity\Article\ArticleType;
use App\Entity\Brand;
use App\Entity\Client\SellerCompany;
use App\Entity\Model;
use App\Entity\User;
use App\Form\Type\Admin\ArticleBannerFormType;
use App\Form\Type\Admin\ArticleImageFormType;
use App\Helper\AdminHelper;
use App\Upload\FileUpload;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityRepository;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ArticleAdmin extends AbstractAdmin
{
    protected $maxPerPage = 192;

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

        $this->uploader->setFolder(FileUpload::ARTICLE);
        $this->helper = new AdminHelper($uploadDirectory);
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        /** @var Article $article */
        $article = $this->getSubject();
        $activateAt = $article->getActivateAt();
        /** @var User */
        $user = $this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken()->getUser();

        if(!($user->hasRole(User::ROLE_ADMIN) || $user->hasRole(User::ROLE_SHOW_POSTS_HOMEPAGE)) && $article->getId() && $article->getCreator() !== $user){
            throw new AccessDeniedException('У вас нет доступа к этим данным!');
        }

        $brand = $article->getDetail() ? $article->getDetail()->getBrand() : null;
        $model = $article->getDetail() ? $article->getDetail()->getModel() : null;

        $formMapper->add('title', TextType::class, ['label' => 'title']);
        $formMapper->add('description', TextType::class, ['label' => 'description']);
        $formMapper->add('headline1', TextType::class, ['label' => 'Основной заголовок статьи']);
        $formMapper->add('headline2', TextType::class, ['label' => 'Второй заголовок (подзаголовок) статьи']);
        $formMapper->add('author', TextType::class, [
            'label' => 'Автор статьи',
            'required' => false,
        ]);
        $formMapper->add('mainArticleImage', ArticleImageFormType::class, [
            'label' => 'Пилотное изображение',
            'required' => false,
            "useAllFields" => true,
            "attr" => [
                "class" => "single-image-for-cropper-container article-images-container",
                "data-image-width" => 540,
                "data-image-height" => 360,
            ],
        ]);
        $formMapper->add('articleImages', CollectionType::class, [
            'label' => 'Другие изображения',
            'entry_type' => ArticleImageFormType::class,
            'allow_delete' => true,
            'allow_add' => true,
            'required' => false,
            'attr' => [
                "class" => "article-images-container",
                "data-image-width" => 540,
                "data-image-height" => 360,
            ],
        ]);
        $formMapper->add('banners', CollectionType::class, [
            'label' => 'Баннеры',
            'entry_type' => ArticleBannerFormType::class,
            'allow_delete' => true,
            'allow_add' => true,
            'required' => false,
        ]);
        $formMapper->add('detail.themes', EntityType::class, [
            'label' => false,
            'class' => ArticleTheme::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('th')
                    ->where("th.isEnable = :trueValue")
                    ->setParameter("trueValue", true)
                    ->orderBy('th.orderIndex', 'ASC');
            },
            'choice_label' => 'theme',
            'multiple' => true,
            'expanded' => true,
            'required' => false,
        ]);
        $formMapper->add('isActive', CheckboxType::class, [
            'attr' => ['class' => "top-step is-active-checkbox"],
            'label' => 'Активная',
            'required' => false]);
        $formMapper->add('activateAtDate', ChoiceType::class, [
            'attr' => [
                'style' => "width: 10%; float: left; margin-right: 20px;",
                'class' => "activate-at-date activate-at-block",
                'data-sonata-select2' => 'false',
            ],
            'label' => 'Активировать в',
            'choices' => $this->getActivateDates($activateAt),
            'data' => $activateAt ? $activateAt->format("d.m.Y") : null,
            'mapped' => false,
            'required' => false
        ]);
        $formMapper->add('activateAtTime', ChoiceType::class, [
            'attr' => [
                'style' => "width: 7%;",
                'data-sonata-select2' => 'false',
                'class' => "activate-at-time activate-at-block",
            ],
            'label' => "в",
            'label_attr' => [
                "style" => "float: left; margin-right: 16px; ",
            ],
            'choices' => $this->getActivateTimes(),
            'data' => $activateAt ? (int)$activateAt->format("H") : null,
            'mapped' => false,
            'required' => false
        ]);
        $formMapper->add('detail.types', EntityType::class, [
            'attr' => ['class' => "detailt-types"],
            'label' => false,
            'class' => ArticleType::class,
            'choice_label' => 'name',
            'multiple' => true,
            'expanded' => true,
            'required' => false,
        ]);
        $formMapper->add('detail.brand', EntityType::class, [
            'label' => "Марка",
            'class' => Brand::class,
            'choice_label' => 'name',
            'required' => false,
            'attr' => [
                'data-sonata-select2' => 'false',
                "class" => "article-image-detail-brand-select",
            ]
        ]);
        $formMapper->add('model', EntityType::class, [
            'label' => "Модель",
            'class' => Model::class,
            'choice_label' => 'name',
            'query_builder' => function (EntityRepository $er) use ($brand) {
                return $er->createQueryBuilder('m')
                    ->where("m.brand = :brand")
                    ->setParameter("brand", $brand);
            },
            'required' => false,
            'attr' => [
                'data-sonata-select2' => 'false',
                "class" => "article-image-detail-model-select",
            ],
            'mapped' => false,
            'data' => $model,
        ]);
        $formMapper->add('detail.activityType', ChoiceType::class, [
            'label' => "Вид деятельности",
            'choices' => $this->getChoicesActivities(),
            'required' => false,
            'attr' => [
                'data-sonata-select2' => 'false',
            ]
        ]);

        $formMapper->getFormBuilder()->addEventListener(FormEvents::PRE_SUBMIT,
            function (FormEvent $event) use ($formMapper) {
                $form = $event->getForm();

                $modelType =  $formMapper->getFormBuilder()->getFormFactory()->createNamed('model', EntityType::class, null, [
                    'label' => "Модель",
                    'class' => Model::class,
                    'choice_label' => 'name',
                    'required' => false,
                    'attr' => [
                        'data-sonata-select2' => 'false',
                        "class" => "article-image-detail-model-select",
                    ],
                    'mapped' => false,
                    'auto_initialize' => false,
                ]);
                $form->add($modelType);
            });
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('idObject', null, ['label' => 'ID', 'sortable' => false, 'mapped' => false, 'template' => 'admin/general/id_object.html.twig']);
        $listMapper->addIdentifier('headline1', TextType::class, ['label' => 'Название', 'sortable' => false]);
        $listMapper->addIdentifier('author', TextType::class, ['label' => 'Автор', 'sortable' => false]);
        $listMapper->addIdentifier('createdAt', 'datetime', [
            'label' => 'Дата',
            'sortable' => false,
            'format' => 'Y-m-d H:i',
        ]);
        $listMapper->addIdentifier('isActive', 'boolean', ['label' => 'Активная', 'sortable' => false]);
        $listMapper->addIdentifier('updatedAt', 'datetime', [
            'label' => 'Редактор',
            'sortable' => false,
            'format' => 'Y-m-d H:i',
        ]);
        $listMapper->addIdentifier('views', TextType::class, ['label' => 'Просмотры', 'sortable' => false]);
        $listMapper->addIdentifier('directViews', TextType::class, ['label' => 'Прямые входы', 'sortable' => false]);
    }

    public function prePersist($article)
    {
        /** @var User */
        $user = $this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken()->getUser();
        $article->setCreator($user);

        $this->uploadFiles($this->getForm(), $article);
        $this->saveModel($this->getForm()->get("model")->getData(), $article);
        $this->addActivateAt($this->getForm(), $article);
    }

    public function preUpdate($article)
    {
        $this->uploadFiles($this->getForm(), $article);
        $this->saveModel($this->getForm()->get("model")->getData(), $article);
        $this->addActivateAt($this->getForm(), $article);

        $article->setUpdatedAt(new DateTime());
    }

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);
        $rootAlias = $query->getRootAlias();
        /** @var User */
        $user = $this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken()->getUser();

        if(!($user->hasRole(User::ROLE_ADMIN) || $user->hasRole(User::ROLE_SHOW_POSTS_HOMEPAGE))) {
            $query->where($rootAlias . '.creator = :user')
                ->setParameter('user', $user);
        }

        $query->orderBy($rootAlias . '.updatedAt', 'DESC');

        return $query;
    }

    protected function uploadFiles(Form $form, Article $article)
    {
        $this->uploader->setFolder(FileUpload::ARTICLE);
        $this->uploadMainImage($form->get('mainArticleImage')->get("filePath")->getData(), $article);
        $this->uploadArticleImages($form->get('articleImages'), $article);
        $this->uploadArticleBanners($form->get('banners'), $article);
    }

    private function uploadMainImage($file, Article $article)
    {
        if($file){
            $path = $this->uploader->upload($file);

            $article->getMainArticleImage()->setImage($path);
        }
    }

    private function uploadArticleImages(Form $images, Article $article)
    {
        foreach ($images as $image){
            $file = $image->get("filePath")->getData();
            /** @var ArticleImage $articleImage */
            $articleImage = $image->getData();

            if($file){
                $path = $this->uploader->upload($file);

                $articleImage->setImage($path);
            }


            $articleImage->setArticle($article);
        }
    }

    private function uploadArticleBanners(Form $bannerForms, Article $article)
    {
        foreach ($bannerForms as $bannerForm){
            $file = $bannerForm->get("file")->getData();
            /** @var ArticleImage $banner */
            $banner = $bannerForm->getData();

            if($file){
                $path = $this->uploader->upload($file);

                $banner->setImage($path);
            }


            $banner->setArticle($article);
        }
    }

    private function saveModel($model, Article $article)
    {
        $article->getDetail()->setModel($model);
    }

    private function getChoicesActivities()
    {
        $array = array_values(SellerCompany::$activities);

        return array_combine($array, $array);
    }

    private function getActivateDates($activatedAt)
    {
        $now = new DateTime("now");

        $choices = [];

        $days = 8;

        while($days){
            $choices[$now->format("d.m.Y")] = $now->format("d.m.Y");

            $now->add(new DateInterval("P1D"));

            --$days;
        }

        if($activatedAt instanceof DateTime && ($activatedAt < new DateTime("now") || $activatedAt >= $now)){
            $choices = array_merge([
                $activatedAt->format("d.m.Y") => $activatedAt->format("d.m.Y")
            ], $choices);
        }

        return $choices;
    }

    private function getActivateTimes()
    {
        return [
            "00:00" => 0,
            "03:00" => 3,
            "06:00" => 6,
            "09:00" => 9,
            "12:00" => 12,
            "15:00" => 15,
            "18:00" => 18,
            "21:00" => 21,
        ];
    }

    private function addActivateAt(Form $form, Article $article)
    {
        $activateAt = $article->getActivateAt();
        $date = $form->get("activateAtDate")->getData();
        $time = $form->get("activateAtTime")->getData();

        if(!$date || $time === null){
            $article->setActivateAt(null);

            return false;
        }

        $newDate = new DateTime($date);

        $newDate->setTime($time, 0);

        $article->setActivateAt($newDate);

        return true;
    }

    public function getBatchActions()
    {
        $actions = parent::getBatchActions();

        unset($actions["delete"]);

        return $actions;
    }

    public function configureRoutes(RouteCollection $collection) {
        $collection->remove('export');
    }
}