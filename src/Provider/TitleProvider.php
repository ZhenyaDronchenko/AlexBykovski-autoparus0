<?php

namespace App\Provider;

use App\Transformer\VariableTransformer;
use Doctrine\ORM\EntityManagerInterface;

class TitleProvider
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var VariableTransformer */
    private $transformer;

    /**
     * TitleProvider constructor.
     * @param EntityManagerInterface $em
     * @param VariableTransformer $transformer
     */
    public function __construct(EntityManagerInterface $em, VariableTransformer $transformer)
    {
        $this->em = $em;
        $this->transformer = $transformer;
    }

    public function getSinglePageTitle($class, array $parameters = [])
    {
        $page = $this->em->getRepository($class)->findAll();

        if(!count($page) || get_class($page[0]) !== $class || !method_exists($page[0], "getTitle")){
            return "";
        }

        return $this->transformer->transformPage($page[0]->getTitle(), $parameters);
    }
}