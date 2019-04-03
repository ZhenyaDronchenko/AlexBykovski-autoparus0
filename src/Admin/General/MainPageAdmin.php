<?php

namespace App\Admin\General;

use App\Entity\General\MainPage;
use App\Entity\General\MainPageAction;
use App\Form\Type\Admin\MainPageActionType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MainPageAdmin extends AbstractAdmin
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
        /** @var MainPage $page */
        $page = $this->getSubject();

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
    }

    protected function updateActions(MainPage $page){
        $this->removeOldActions();

        /** @var MainPageAction $action */
        foreach ($page->getActions() as $action){
            $action->setMainPage($page);
        }
    }

    protected function removeOldActions(){
        $oldActions = $this->em->getRepository(MainPageAction::class)->findAll();

        foreach ($oldActions as $action){
            $this->em->remove($action);
        }
    }
}