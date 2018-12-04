<?php

namespace App\Provider\Integration;

use Symfony\Component\DomCrawler\Crawler;

class BamperSuggestionProvider
{
    const URL_TEMPLATE = "https://bamper.by/zchbu/zapchast_%s/marka_%s/model_%s/podzakaz_1/?sort=PRICE-ASC";
    //const URL_TEMPLATE = "https://bamper.by/zchbu/zapchast_%s/marka_%s/model_%s/podzakaz_1/?sort=PRICE_ASC";
    const URL_TEMPLATE_WITH_CITY = "https://bamper.by/zchbu/zapchast_%s/marka_%s/model_%s/gorod_%s/podzakaz_1/?sort=PRICE-ASC";
    const URL_BASE = "https://bamper.by";

    public function provide($brandUrl, $modelUrl, $sparePartUrl, $cityUrl = null)
    {
        if(!$brandUrl || !$modelUrl || !$sparePartUrl){
            return [];
        }

        $crawler = $this->getExternalData($brandUrl, $modelUrl, $sparePartUrl, $cityUrl);

        $suggestions = $this->parseSuggestions($crawler);

        return $suggestions;
    }

    public function getPhoneNumbers($url)
    {
        $response = $this->request(self::URL_BASE . $url);

        $phoneObjs = (new Crawler($response))->filter('#seller_info div.user-info div.user-ads-action a.btn-phone');

        $phones = [];

        $phoneObjs->each(function (Crawler $node, $i) use (&$phones) {
            $phones[] = trim(str_replace("tel:", "", $node->attr("href")));
        });

        return $phones;
    }

    protected function getExternalData($brandUrl, $modelUrl, $sparePartUrl, $cityUrl)
    {
        ini_set('xdebug.var_display_max_depth', '100');
        ini_set('xdebug.var_display_max_children', '2560');
        ini_set('xdebug.var_display_max_data', '1024000');

        if($cityUrl){
            $url = sprintf(self::URL_TEMPLATE_WITH_CITY, $sparePartUrl, $brandUrl, $modelUrl, $cityUrl);
        }
        else{
            $url = sprintf(self::URL_TEMPLATE, $sparePartUrl, $brandUrl, $modelUrl);
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

        return [
            "url" => $url,
            "description" => trim($description),
            "price" => number_format($price, 2),
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