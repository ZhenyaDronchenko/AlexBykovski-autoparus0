<?php

namespace App\Twig;

use App\Entity\Advert\AutoSparePart\AutoSparePartGeneralAdvert;
use App\Entity\Advert\AutoSparePart\AutoSparePartSpecificAdvert;
use App\Entity\Client\Client;
use Doctrine\ORM\EntityManagerInterface;
use Twig_Extension;
use Twig_Function;

class CountAdvertsProviderExtension extends Twig_Extension
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
            new Twig_Function('adverts_count', [$this, 'getCountAdverts']),
        );
    }

    public function getCountAdverts(Client $client, $type)
    {
        $advertDetail = $client->getSellerData()->getAdvertDetail();

        if($type === "specific") {
            return $this->em->getRepository(AutoSparePartSpecificAdvert::class)->countForUser($advertDetail);
        }

        return $this->em->getRepository(AutoSparePartGeneralAdvert::class)->countForUser($advertDetail);
    }
}