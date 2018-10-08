<?php

namespace App\Form\DataTransformer;

use App\Entity\Brand;
use App\Entity\EngineType;
use App\Entity\VehicleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class IdToEngineTypeTransformer implements DataTransformerInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Transforms an object (engineType) to a string (id).
     *
     * @param  EngineType|null engineType
     *
     * @return string
     */
    public function transform($engineType)
    {
        if ($engineType instanceof EngineType) {
            return (string)$engineType->getId();
        }

        return null;
    }

    /**
     * Transforms a string (id) to an object (engineType).
     *
     * @param  string $type
     * @return EngineType|null
     * @throws TransformationFailedException if object (engineType) is not found.
     */
    public function reverseTransform($id)
    {
        if (!$id) {
            return null;
        }

        $engineType = $this->em->getRepository(EngineType::class)->find($id);

        if($engineType instanceof EngineType){
            return $engineType;
        }

        throw new TransformationFailedException(sprintf(
            'A engineType with id "%s" does not exist!',
            $id
        ));
    }
}