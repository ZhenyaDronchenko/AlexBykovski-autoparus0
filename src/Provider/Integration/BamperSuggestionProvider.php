<?php

namespace App\Provider\Integration;

use App\Entity\Brand;
use App\Entity\City;
use App\Entity\Model;
use App\Entity\SparePart;
use Symfony\Component\DomCrawler\Crawler;

class BamperSuggestionProvider
{
    const URL_TEMPLATE = "https://bamper.by/zchbu/zapchast_%s/marka_%s/model_%s/god_%s-%s/podzakaz_1/?sort=PRICE-ASC";
    //const URL_TEMPLATE = "https://bamper.by/zchbu/zapchast_%s/marka_%s/model_%s/podzakaz_1/?sort=PRICE_ASC";
    const URL_TEMPLATE_WITH_CITY = "https://bamper.by/zchbu/zapchast_%s/marka_%s/model_%s/god_%s-%s/gorod_%s/podzakaz_1/?sort=PRICE-ASC";
    const URL_BASE = "https://bamper.by";

    public function provide(Brand $brand, Model $model, SparePart $sparePart, City $city = null)
    {
        if(!$brand || !$model || !$sparePart){
            return [];
        }

        $cityUrl = $city instanceof City ? $city->getUrlConnectBamperIncludeBase() : null;
        $modelYearFrom = $model->getTechnicalData()->getYearFrom();
        $modelYearTo = $model->getTechnicalData()->getYearTo();
        $crawler = $this->getExternalData($brand->getUrlConnectBamperIncludeBase(), $model->getUrlConnectBamperIncludeBase(), $modelYearFrom, $modelYearTo, $sparePart->getUrlConnectBamperIncludeBase(), $cityUrl);

        $suggestions = $this->parseSuggestions($crawler);

        return $suggestions;
    }

    public function getPhoneNumbers($url)
    {
        $response = $this->request(self::URL_BASE . $url);

        $phoneObjs = (new Crawler($response))->filter('#seller_info div.user-info div.user-ads-action a.btn-phone');

        $phones = [];

        $phoneObjs->each(function (Crawler $node, $i) use (&$phones) {
            $phone = trim(str_replace("tel:", "", $node->attr("href")));

            $phones[] = preg_replace('/(\(\d{2}\))/', ' ${1} ', $phone);
        });

        return $phones;
    }

    protected function getExternalData($brandUrl, $modelUrl, $modelYearFrom, $modelYearTo, $sparePartUrl, $cityUrl)
    {
        if($cityUrl){
            $url = sprintf(self::URL_TEMPLATE_WITH_CITY, $sparePartUrl, $brandUrl, $modelUrl, $modelYearFrom, $modelYearTo, $cityUrl);
        }
        else{
            $url = sprintf(self::URL_TEMPLATE, $sparePartUrl, $brandUrl, $modelUrl, $modelYearFrom, $modelYearTo);
        }

        $response = $this->request($url);

        $crawler = new Crawler($response);

        if(!$crawler->filter("select#zapchast > option[selected]")->count() ||
            !$crawler->filter("select#marka > option[selected]")->count() ||
            !$crawler->filter("select#model > option[selected]")->count() ||
            $cityUrl && !$crawler->filter("select#gorod > option[selected]")->count()){
           return new Crawler();
        }

        return $crawler->filter('#allAds > div.item-list');
    }

    protected function parseSuggestions(Crawler $crawler)
    {
        $suggestions = [];

        $crawler->each(function (Crawler $node, $i) use (&$suggestions) {
            $suggestions[] = $this->getAdData($node);
        });

        return $suggestions;
    }

    protected function getAdData(Crawler $node)
    {
        $linkObj = $node->filter('div.add-desc-box h5.add-title a');
        $descriptionBox = $node->filter('div.add-desc-box span.info-row > div');
        $descriptionBox2 = $node->filter('div.add-desc-box span.info-row > span')->reduce(function (Crawler $node, $i) {
            return !$node->filter("i.icon-clock")->count() && !$node->filter("a.link_ads_city")->count();
        });
        $priceBox = $node->filter('div.price-box > h2.item-price > span');
        $cityBox = $node->filter('div.add-desc-box span.info-row > span.city > a.link_ads_city');

        $url = $linkObj->attr("href");
        $description = "";

        $descriptionBox->each(function (Crawler $node, $i) use (&$description) {
            $description .= trim($node->text()) . ' ';
        });

        $descriptionBox2->each(function (Crawler $node, $i) use (&$description) {
            $description .= trim($node->text()) . ' ';
        });

        $priceBox->filter('div')->each(function (Crawler $crawlerChild) {
            foreach ($crawlerChild as $node) {
                $node->parentNode->removeChild($node);
            }
        });

        $price = (float)preg_replace("/[^0-9]/", "", trim($priceBox->text()) ) / 100;

        $city = trim($cityBox->last()->text());

        return [
            "url" => $url,
            "description" => trim($description),
            "price" => number_format($price, 2),
            "city" => $city,
        ];
    }

    protected function request($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        curl_close($ch);

        return $response;
    }
}