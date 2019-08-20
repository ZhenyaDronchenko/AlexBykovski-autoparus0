<?php

namespace App\Command;

use App\Entity\Advert\CurrencyRate;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateCurrencyCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('app:update:currency')
            ->setDescription('Update currency rates');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        /** @var CurrencyRate[] $currencies */
        $currencies = $em->getRepository(CurrencyRate::class)->findAll();
        /** @var CurrencyRate $currencyUSD */
        $currencyUSD = $em->getRepository(CurrencyRate::class)->findOneBy(["code" => CurrencyRate::USD_CODE]);

        // default for USD
        $newRates = $this->getNewRates();

        if(!$newRates || !count($newRates)){
            $output->writeln("<info>ERROR</info>");

            return false;
        }

        $currencyUSD->setRate((float)$newRates["USDBYN"]);

        /** @var CurrencyRate $currency */
        foreach ($currencies as $currency){
            $usdScale = (float)$newRates["USD" . $currency->getCode()];

            $currency->setRate($currencyUSD->getRate() / $usdScale);
        }

        $em->flush();

        $output->writeln("<info>Done</info>");
    }

    private function getNewRates()
    {
// Initialize CURL:
        $ch = curl_init('http://apilayer.net/api/live?access_key=' . CurrencyRate::CURRENCY_LAYER_API_KEY);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Store the data:
        $json = curl_exec($ch);
        curl_close($ch);

// Decode JSON response:
        $exchangeRates = json_decode($json, true);

// Access the exchange rate values, e.g. GBP:
        return $exchangeRates['quotes'];
    }
}