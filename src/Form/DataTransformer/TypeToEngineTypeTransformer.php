<?php

namespace App\Form\DataTransformer;

use App\Entity\Brand;
use App\Entity\EngineType;
use App\Entity\VehicleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class TypeToEngineTypeTransformer implements DataTransformerInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Transforms an object (engineType) to a string (type).
     *
     * @param  EngineType|null engineType
     *
     * @return string
     */
    public function transform($engineType)
    {
        if ($engineType instanceof EngineType) {
            return $engineType->getType();
        }

        return null;
    }

    /**
     * Transforms a string (type) to an object (engineType).
     *
     * @param  string $type
     * @return EngineType|null
     * @throws TransformationFailedException if object (engineType) is not found.
     */
    public function reverseTransform($type)
    {
        if (!$type) {
            return null;
        }

        $engineType = $this->em->getRepository(EngineType::class)->findOneBy(["type" => $type]);

        if($engineType instanceof EngineType){
            return $engineType;
        }

        throw new TransformationFailedException(sprintf(
            'A engineType with type "%s" does not exist!',
            $type
        ));
    }
}