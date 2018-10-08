<?php

namespace App\Form\DataTransformer;

use App\Entity\Brand;
use App\Entity\Model;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class NameToModelTransformer implements DataTransformerInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Transforms an object (model) to a string (name).
     *
     * @param  Model|null $model
     *
     * @return string
     */
    public function transform($model)
    {
        if ($model instanceof Model) {
            return $model->getName();
        }

        return null;
    }

    /**
     * Transforms a string (name) to an object (model).
     *
     * @param  string $name
     * @return Model|null
     * @throws TransformationFailedException if object (brand) is not found.
     */
    public function reverseTransform($name)
    {
        if (!$name) {
            return null;
        }

        $model = $this->em->getRepository(Model::class)->findOneBy(["name" => $name]);

        if($model instanceof Model){
            return $model;
        }

        throw new TransformationFailedException(sprintf(
            'A model with name "%s" does not exist!',
            $name
        ));
    }
}