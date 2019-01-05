<?php

namespace App\Form\DataTransformer;

use App\Entity\DriveType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class IdToDriveTypeTransformer implements DataTransformerInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Transforms an object (driveType) to a string (id).
     *
     * @param  DriveType|null driveType
     *
     * @return string
     */
    public function transform($driveType)
    {
        if ($driveType instanceof DriveType) {
            return (string)$driveType->getId();
        }

        return null;
    }

    /**
     * Transforms a string (id) to an object (driveType).
     *
     * @param  string $id
     *
     * @return DriveType|null
     *
     * @throws TransformationFailedException if object (driveType) is not found.
     */
    public function reverseTransform($id)
    {
        if (!$id) {
            return null;
        }

        $driveType = $this->em->getRepository(DriveType::class)->find($id);

        if($driveType instanceof DriveType){
            return $driveType;
        }

        throw new TransformationFailedException(sprintf(
            'A driveType with id "%s" does not exist!',
            $id
        ));
    }
}