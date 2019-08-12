<?php

namespace App\Form\Type\Admin;

use App\Entity\Article\ArticleBanner;
use App\Entity\General\MainPageBanner;
use App\Helper\AdminHelper;
use App\Upload\FileUpload;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MainPageBannerFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $helper = new AdminHelper('/' . FileUpload::IMAGE_FOLDER);

        $builder
            ->add(
                'file',
                FileType::class,
                [
                    'label' => 'Баннер',
                    'mapped' => false,
                    "sonata_help" => "",
                    "attr" => [
                        "class" => "file-for-cropper",
                    ],
                ])
            ->add('link', TextType::class, [
                'label' => "Ссылка баннера",
            ])
            ->add('alt', TextType::class, [
                'label' => "Альт",
            ])
            ->add('filePath', HiddenType::class,
                [
                    'label' => false,
                    'required' => false,
                    'mapped' => false,
                    "attr" => [
                        "class" => "file-path-for-cropper",
                    ],
            ]);

        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) use ($builder, $helper) {
            $object = $event->getData();
            $form = $event->getForm();

            if(!($object instanceof MainPageBanner)){
                return true;
            }

            $helpImage = $object->getImage() ? $helper->getImagesHelp($helper->getImagesData($object)) : "";

            $form
                ->add('file', FileType::class,
                    [
                        'label' => 'Изображение',
                        'required' => false,
                        'mapped' => false,
                        "sonata_help" => $helpImage,
                    ]);
        });
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MainPageBanner::class,
            'validation_groups' => [],
        ]);
    }
}