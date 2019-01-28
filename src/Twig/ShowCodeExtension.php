<?php

namespace App\Twig;

use App\Entity\SEO\BodyCode;
use App\Entity\SEO\HeadCode;
use Doctrine\ORM\EntityManagerInterface;
use Twig_Extension;
use Twig_Function;

class ShowCodeExtension extends Twig_Extension
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
            new Twig_Function('show_code', [$this, 'showCode']),
        );
    }

    public function showCode($type)
    {
        switch($type){
            case "head":
                /** @var HeadCode $headCode */
                $headCode = $this->em->getRepository(HeadCode::class)->findAll()[0];

                return $headCode->getCode();
            case "body":
                /** @var BodyCode $bodyCode */
                $bodyCode = $this->em->getRepository(BodyCode::class)->findAll()[0];

                return $bodyCode->getCode();

            default:
                return "";
        }
    }
}