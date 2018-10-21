<?php

namespace App\Twig;

use App\Entity\DefaultText;
use Doctrine\ORM\EntityManagerInterface;
use Twig_Extension;
use Twig_Function;

class DefaultTextExtension extends Twig_Extension
{
    /** @var EntityManagerInterface */
    private $em;

    /**
     * ShowCodeExtension constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getFunctions()
    {
        return array(
            new Twig_Function('default_text', [$this, 'getDefaultText']),
        );
    }

    public function getDefaultText($id)
    {
        $defaultText = $this->em->getRepository(DefaultText::class)->find($id);

        return $defaultText instanceof DefaultText ? $defaultText : new DefaultText();

    }
}