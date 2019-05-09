<?php

namespace App\Twig;

use Doctrine\ORM\EntityManagerInterface;
use Twig_Extension;
use Twig_Function;

class ObjectProviderExtension extends Twig_Extension
{
    /** @var EntityManagerInterface */
    private $em;

    /**
     * ShowCodeExtension constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getFunctions()
    {
        return array(
            new Twig_Function('get_object', [$this, 'getObject']),
        );
    }

    public function getObject($class, $method, $parameters)
    {
        return $this->em->getRepository($class)->$method($parameters);
    }
}