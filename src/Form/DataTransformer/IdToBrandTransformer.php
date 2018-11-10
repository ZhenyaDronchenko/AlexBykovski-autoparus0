<?php

namespace App\Form\DataTransformer;

use App\Entity\Brand;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class IdToBrandTransformer implements DataTransformerInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Transforms an object (brand) to a string (id).
     *
     * @param  Brand|null $brand
     *
     * @return string
     */
    public function transform($brand)
    {
        if ($brand instanceof Brand) {
            return (int)$brand->getId();
        }

        return 0;
    }

    /**
     * Transforms a string (id) to an object (brand).
     *
     * @param  string $id
     * @return Brand|null
     * @throws TransformationFailedException if object (brand) is not found.
     */
    public function reverseTransform($id)
    {
        if (!$id) {
            return null;
        }

        $brand = $this->em->getRepository(Brand::class)->find($id);

        if(!$brand && (int)$id == -1){
            $brand = new Brand();
            $brand->setId(-1);
        }

        if($brand instanceof Brand){
            return $brand;
        }

        throw new TransformationFailedException(sprintf(
            'A brand with id "%s" does not exist!',
            $id
        ));
    }
}