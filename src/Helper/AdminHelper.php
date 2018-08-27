<?php

namespace App\Helper;


class AdminHelper
{
    private $uploadDirectory;

    /**
     * AdminHelper constructor.
     * @param string $uploadDirectory
     */
    public function __construct($uploadDirectory)
    {
        $this->uploadDirectory = $uploadDirectory;
    }

    public function getImagesHelp(array $images){
        $help = "";

        if(count($images)){
            $help .= '<div class="row">';

            foreach($images as $image){
                $help .= $this->getSingleImageHelp($image);
            }

            $help .= '</div>';
        }

        return $help;
    }

    protected function getSingleImageHelp($image){
        return '<div class="col-md-4">
                    <img class="thumbnail" src="' . $this->uploadDirectory . $image . '" alt="Lights" style="height: 150px;">
                </div>';
    }
}