<?php

namespace App\Twig;

use App\Provider\TitleProvider;
use DOMDocument;
use Twig_Extension;
use Twig_Function;

class TitleTransformerExtension extends Twig_Extension
{
    const LINK_PARAMETER = "link";

    /** @var TitleProvider $titleProvider */
    private $titleProvider;

    /**
     * ShowCodeExtension constructor.
     *
     * @param TitleProvider $titleProvider
     */
    public function __construct(TitleProvider $titleProvider)
    {
        $this->titleProvider = $titleProvider;
    }

    public function getFunctions()
    {
        return array(
            new Twig_Function('title_provider', [$this, 'getClassTitle']),
        );
    }

    public function getClassTitle($element, $parameters = [])
    {
        if(is_array($element) && array_key_exists(self::LINK_PARAMETER, $element)){
            return $this->getTitleFromLink($element[self::LINK_PARAMETER]);
        }

        return $this->titleProvider->getSinglePageTitle($element, $parameters);
    }

    private function getTitleFromLink($link)
    {
        $html = $this->fileGetContentsCurl($link);

        if(!$html){
            return "";
        }

        $doc = new DOMDocument();
        @$doc->loadHTML($html);
        $nodes = $doc->getElementsByTagName('title');

        return $nodes->item(0)->nodeValue;
    }

    private function fileGetContentsCurl($url)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }
}