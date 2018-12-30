<?php

namespace App\Form\DataTransformer;

use App\Entity\GearBoxType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class IdToGearBoxTypeTransformer implements DataTransformerInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Transforms an object (gearBoxType) to a string (id).
     *
     * @param  GearBoxType|null gearBoxType
     *
     * @return string
     */
    public function transform($gearBoxType)
    {
        if ($gearBoxType instanceof GearBoxType) {
            return (string)$gearBoxType->getId();
        }

        return null;
    }

    /**
     * Transforms a string (id) to an object (gearBoxType).
     *
     * @param  string $id
     *
     * @return GearBoxType|null
     *
     * @throws TransformationFailedException if object (gearBoxType) is not found.
     */
    public function reverseTransform($id)
    {
        if (!$id) {
            return null;
        }

        $gearBoxType = $this->em->getRepository(GearBoxType::class)->find($id);

        if($gearBoxType instanceof GearBoxType){
            return $gearBoxType;
        }

        throw new TransformationFailedException(sprintf(
            'A gearBoxType with id "%s" does not exist!',
            $id
        ));
    }
}