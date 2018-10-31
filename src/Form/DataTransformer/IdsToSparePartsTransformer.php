<?php

namespace App\Form\DataTransformer;

use App\Entity\SparePart;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class IdsToSparePartsTransformer implements DataTransformerInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Transforms an array (spareParts) to a array (ids).
     *
     * @param  Collection $spareParts
     *
     * @return array
     */
    public function transform($spareParts)
    {
        $ids = [];

        /** @var SparePart $sparePart */
        foreach ($spareParts as $sparePart){
            $ids[] = $sparePart->getId();
        }

        return $ids;
    }

    /**
     * Transforms a array (ids) to an array (spareParts).
     *
     * @param  array $ids
     *
     * @return Collection
     *
     * @throws TransformationFailedException if array (spareParts) is not found.
     */
    public function reverseTransform($ids)
    {
        $spareParts = new ArrayCollection();

        if (!is_array($ids) || !count($ids)) {
            return $spareParts;
        }

        foreach ($ids as $id){
            $sparePart = $this->em->getRepository(SparePart::class)->find($id);

            if($sparePart instanceof SparePart){
                $spareParts->add($sparePart);
            }
        }

        return $spareParts;
    }
}