<?php

namespace App\Form\DataTransformer;

use App\Entity\Brand;
use App\Entity\VehicleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class TypeToVehicleTypeTransformer implements DataTransformerInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Transforms an object (vehicleType) to a string (type).
     *
     * @param  VehicleType|null vehicleType
     *
     * @return string
     */
    public function transform($vehicleType)
    {
        if ($vehicleType instanceof VehicleType) {
            return $vehicleType->getType();
        }

        return null;
    }

    /**
     * Transforms a string (type) to an object (vehicleType).
     *
     * @param  string $type
     * @return VehicleType|null
     * @throws TransformationFailedException if object (vehicleType) is not found.
     */
    public function reverseTransform($type)
    {
        if (!$type) {
            return null;
        }

        $vehicleType = $this->em->getRepository(VehicleType::class)->findOneBy(["type" => $type]);

        if($vehicleType instanceof VehicleType){
            return $vehicleType;
        }

        throw new TransformationFailedException(sprintf(
            'A vehicleType with type "%s" does not exist!',
            $type
        ));
    }
}