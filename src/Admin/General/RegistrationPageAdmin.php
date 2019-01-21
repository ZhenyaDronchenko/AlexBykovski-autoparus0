<?php

namespace App\Admin\General;

use App\Entity\General\EmailDomain;
use App\Form\Type\EmailDomainFormType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\CollectionType;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RegistrationPageAdmin extends AbstractAdmin
{
    /** @var EntityManagerInterface */
    private $em;

    public function __construct(
        string $code,
        string $class,
        string $baseControllerName,
        EntityManagerInterface $em
    )
    {
        parent::__construct($code, $class, $baseControllerName);

        $this->em = $em;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('title', TextType::class, ['label' => 'Title']);
        $formMapper->add('description', TextType::class, ['label' => 'Description']);
        $formMapper->add('headline', TextType::class, ['label' => 'Заголовок']);
        $formMapper->add('textBottom', CKEditorType::class, ['label' => 'Текст внизу страницы']);
        $formMapper->add('emailDomains', CollectionType::class, [
            'label' => 'Соответствия окончания адреса почты и адреса почтового адреса',
            'entry_type' => EmailDomainFormType::class,
            'allow_delete' => true,
            'allow_add' => true,
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
        $emailDomains = $page->getEmailDomains();

        $existEmailEnds = [];

        /** @var EmailDomain $emailDomain */
        foreach ($emailDomains as $emailDomain){
            $emailDomain->setPage($page);
            $existEmailEnds[] = $emailDomain->getEmailEnd();
        }

        $page->setEmailDomains($emailDomains);

        $this->em->getRepository(EmailDomain::class)->deleteAbsentByEmailEnds($existEmailEnds);
    }
}