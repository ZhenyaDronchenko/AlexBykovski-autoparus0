<?php

namespace App\Form\DataTransformer;

use App\Entity\SparePart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class NameToSparePartTransformer implements DataTransformerInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Transforms an object (sparePart) to a string (name).
     *
     * @param  SparePart|null sparePart
     *
     * @return string
     */
    public function transform($sparePart)
    {
        if ($sparePart instanceof SparePart) {
            return (string)$sparePart->getName();
        }

        return null;
    }

    /**
     * Transforms a string (name) to an object (sparePart).
     *
     * @param  string $name
     *
     * @return SparePart|null
     *
     * @throws TransformationFailedException if object (sparePart) is not found.
     */
    public function reverseTransform($name)
    {
        if (!$name) {
            return null;
        }

        $sparePart = $this->em->getRepository(SparePart::class)->findOneBy(["name" => $name]);

        if($sparePart instanceof SparePart){
            return $sparePart;
        }

        throw new TransformationFailedException(sprintf(
            'A sparePart with name "%s" does not exist!',
            $name
        ));
    }
}