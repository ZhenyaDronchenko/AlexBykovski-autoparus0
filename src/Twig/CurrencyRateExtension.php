<?php

namespace App\Twig;

use App\Entity\Advert\AutoSparePart\AutoSparePartGeneralAdvert;
use App\Entity\Advert\AutoSparePart\AutoSparePartSpecificAdvert;
use App\Entity\Advert\CurrencyRate;
use App\Entity\Client\Client;
use Doctrine\ORM\EntityManagerInterface;
use Twig_Extension;
use Twig_Function;

class CurrencyRateExtension extends Twig_Extension
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
            new Twig_Function('currency_rate', [$this, 'getCurrencyRate']),
        );
    }

    public function getCurrencyRate($code, $sumBYN = null)
    {
        $currency = $this->em->getRepository(CurrencyRate::class)->findOneBy(["code" => $code]);

        if(!$currency){
            return null;
        }

        if($sumBYN){
            return $currency->getRate() * (float)$sumBYN;
        }

        return $currency->getRate();
    }
}