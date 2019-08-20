<?php

namespace App\Form\DataTransformer;

use App\Entity\Brand;
use App\Entity\SparePart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class IdToSparePartTransformer implements DataTransformerInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Transforms an object (sparePart) to a string (id).
     *
     * @param  SparePart|null $sparePart
     *
     * @return string
     */
    public function transform($sparePart)
    {
        if ($sparePart instanceof SparePart) {
            return $sparePart->getId();
        }

        return 0;
    }

    /**
     * Transforms a string (id) to an object (sparePart).
     *
     * @param  string $id
     * @return SparePart|null
     * @throws TransformationFailedException if object (sparePart) is not found.
     */
    public function reverseTransform($id)
    {
//        var_dump($id);
//        die;
        if (!$id) {
            return null;
        }

        $sparePart = $this->em->getRepository(SparePart::class)->find($id);

        if($sparePart instanceof SparePart){
            return $sparePart;
        }

        throw new TransformationFailedException(sprintf(
            'A sparePart with id "%s" does not exist!',
            $id
        ));
    }
}