<?php

namespace App\Form\DataTransformer;

use App\Entity\Brand;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class NameToBrandTransformer implements DataTransformerInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Transforms an object (brand) to a string (name).
     *
     * @param  Brand|null $brand
     *
     * @return string
     */
    public function transform($brand)
    {
        if ($brand instanceof Brand) {
            return $brand->getName();
        }

        return null;
    }

    /**
     * Transforms a string (name) to an object (brand).
     *
     * @param  string $name
     * @return Brand|null
     * @throws TransformationFailedException if object (brand) is not found.
     */
    public function reverseTransform($name)
    {
        if (!$name) {
            return null;
        }

        $brand = $this->em->getRepository(Brand::class)->findOneBy(["name" => $name]);

        if($brand instanceof Brand){
            return $brand;
        }

        throw new TransformationFailedException(sprintf(
            'A brand with name "%s" does not exist!',
            $name
        ));
    }
}