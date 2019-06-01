<?php

namespace App\Form\Type\Admin;

use App\Entity\Article\ArticleImage;
use App\Helper\AdminHelper;
use App\Upload\FileUpload;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleImageFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $helper = new AdminHelper('/' . FileUpload::IMAGE_FOLDER);

        $builder
            ->add('file', FileType::class,
                [
                    'label' => 'Изображение',
                    'required' => false,
                    'mapped' => false,
                    "sonata_help" => ""
                ])
            ->add('text', CKEditorType::class, [
                'required' => false,
                'label' => "Текст",
            ]);

        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) use ($builder, $helper) {
            $object = $event->getData();
            $form = $event->getForm();

            if(!($object instanceof ArticleImage)){
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
            'data_class' => ArticleImage::class,
            'validation_groups' => [],
        ]);
    }
}