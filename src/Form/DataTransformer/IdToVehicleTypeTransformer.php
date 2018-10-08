<?php

namespace App\Form\DataTransformer;

use App\Entity\Brand;
use App\Entity\VehicleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class IdToVehicleTypeTransformer implements DataTransformerInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Transforms an object (vehicleType) to a string (id).
     *
     * @param  VehicleType|null vehicleType
     *
     * @return string
     */
    public function transform($vehicleType)
    {
        if ($vehicleType instanceof VehicleType) {
            return (string)$vehicleType->getId();
        }

        return null;
    }

    /**
     * Transforms a string (id) to an object (vehicleType).
     *
     * @param  string $id
     * @return VehicleType|null
     * @throws TransformationFailedException if object (vehicleType) is not found.
     */
    public function reverseTransform($id)
    {
        if (!$id) {
            return null;
        }

        $vehicleType = $this->em->getRepository(VehicleType::class)->find($id);

        if($vehicleType instanceof VehicleType){
            var_dump("should be good");
            return $vehicleType;
        }

        throw new TransformationFailedException(sprintf(
            'A vehicleType with id "%s" does not exist!',
            $id
        ));
    }
}