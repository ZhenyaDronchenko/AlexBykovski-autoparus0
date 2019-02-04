<?php

namespace App\Admin\Error;

use App\Entity\Error\TypeOBD2Error;
use Doctrine\ORM\EntityManagerInterface;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;

class CodeOBD2ErrorAdmin extends AbstractAdmin
{
    protected $parentAssociationMapping = 'type';

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
        $formMapper->add('code', TextType::class, [
            'label' => 'Код ошибки [CODEOBD2]',
            'constraints' => new Regex([
                'pattern' => '/^\d{4}$/',
                'message' =>'Только 4 цифры допустимы'
            ]),
        ]);
        $formMapper->add('url', TextType::class, [
            'label' => 'URL кода ошибки [URLCODEOBD2]',
            'constraints' => new Regex([
                'pattern' => '/^\d{4}$/',
                'message' =>'Только 4 цифры допустимы'
            ]),
        ]);
        $formMapper->add('transcriptRu', TextType::class, ['label' => 'Расшифровка кода ошибки [RUTRANSCRIPTCODEOBD2]']);
        $formMapper->add('transcriptEn', TextType::class, ['label' => 'Расшифровка кода ошибки [ENTRANSCRIPTCODEOBD2]']);
        $formMapper->add('reason', CKEditorType::class, [
            'label' => 'Причины неисправности, связанные с возникновением ошибки [REASON_CODEOBD2]',
            'constraints' => new NotNull(['message' =>'Введите данные']),
        ]);
        $formMapper->add('advice', CKEditorType::class, [
            'label' => 'Советы по устранению неполадок [ADVICE_CODEOBD2]',
            'constraints' => new NotNull(['message' =>'Введите данные']),
        ]);
        $formMapper->add('urlToCatalog', TextType::class, ['label' => 'URL коннект в каталог запчастей [URLCONNECTCODEOBD2]']);
        $formMapper->add('active', CheckboxType::class, ['label' => 'Активный', 'required' => false]);
        $formMapper->add('isOftenSearch', CheckboxType::class, ['label' => 'Частоискомый', 'required' => false]);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('code', 'text', ['label' => 'Код ошибки', 'sortable' => false]);
        $listMapper->addIdentifier('url', 'text', ['label' => 'URL', 'sortable' => false]);
        $listMapper->addIdentifier('transcriptRu', 'text', ['label' => 'Расшифровка кода', 'sortable' => false]);
        $listMapper->addIdentifier('active', 'boolean', ['label' => 'Активный', 'sortable' => false]);
        $listMapper->addIdentifier('isOftenSearch', 'boolean', ['label' => 'Частоискомый', 'sortable' => false]);
    }

    public function getBatchActions()
    {
        $actions = parent::getBatchActions();

        unset($actions["delete"]);

        return $actions;
    }

    public function prePersist($code)
    {
        $type = $this->em->getRepository(TypeOBD2Error::class)->find($this->getRequest()->get("id"));
        $code->setType($type);
    }

    public function createQuery($context = 'list')
    {
        $type = $this->em->getRepository(TypeOBD2Error::class)->find($this->getRequest()->get("id"));
        $query = parent::createQuery($context);

        $query->where($query->getRootAlias() . '.type = :type')
            ->setParameter('type', $type)
            ->orderBy($query->getRootAlias() . ".code", "ASC");

        return $query;
    }
}