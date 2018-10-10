<?php

namespace App\Form\DataTransformer;

use App\Entity\Brand;
use App\Entity\Model;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class IdToModelTransformer implements DataTransformerInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Transforms an object (model) to a string (id).
     *
     * @param  Model|null $model
     *
     * @return string
     */
    public function transform($model)
    {
        if ($model instanceof Model) {
            return $model->getId();
        }

        return null;
    }

    /**
     * Transforms a string (id) to an object (model).
     *
     * @param  string $id
     * @return Model|null
     * @throws TransformationFailedException if object (model) is not found.
     */
    public function reverseTransform($id)
    {
        if (!$id) {
            return null;
        }

        $model = $this->em->getRepository(Model::class)->find($id);

        if($model instanceof Model){
            return $model;
        }

        throw new TransformationFailedException(sprintf(
            'A model with id "%s" does not exist!',
            $id
        ));
    }
}