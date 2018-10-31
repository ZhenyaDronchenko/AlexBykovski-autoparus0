<?php

namespace App\Form\DataTransformer;

use App\Entity\Model;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class IdsToModelsTransformer implements DataTransformerInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Transforms an array (models) to a array (ids).
     *
     * @param  ArrayCollection $models
     *
     * @return array
     */
    public function transform($models)
    {
        $ids = [];

        /** @var Model $model */
        foreach ($models as $model){
            $ids[] = $model->getId();
        }

        return $ids;
    }

    /**
     * Transforms a array (ids) to an array (models).
     *
     * @param  array $ids
     *
     * @return Collection
     * @throws TransformationFailedException if array (models) is not found.
     */
    public function reverseTransform($ids)
    {
        $models = new ArrayCollection();

        if (!is_array($ids) || !count($ids)) {
            return $models;
        }

        foreach ($ids as $id){
            $model = $this->em->getRepository(Model::class)->find($id);

            if($model instanceof Model){
                $models->add($model);
            }
        }

        return $models;
    }
}