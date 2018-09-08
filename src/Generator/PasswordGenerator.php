<?php

namespace App\Generator;

/**
 * Class PasswordGenerator
 * @package App\Generator
 */
class PasswordGenerator
{
    /**
     * @param int $count
     *
     * @return string
     */
    public function generateNumberWordCode($count = 8){
        $chars = 'abcdeifghijklmnopqrstyzABCDEFGHIKLMNOPQRSTYVWZ1234567890';

        return $this->getUniqueCode($count, $chars);
    }

    /**
     * @param int $count
     *
     * @return string
     */
    public function generatePassword($count = 8){
        $chars = 'abcdeifghijklmnopqrstyz=ABCDEFGHIKLMNO+PQRSTYVWZ123456789!';

        return $this->getUniqueCode($count, $chars);
    }

    /**
     * @param int $count
     *
     * @return string
     */
    public function generateOnlyNumberCode($count = 4){
        $chars = '0123456789';

        return $this->getUniqueCode($count, $chars);
    }

    /**
     * @param $count
     * @param $chars
     *
     * @return string
     */
    public function getUniqueCode ($count, $chars){
        $code = '';
        $size = strlen ( $chars ) - 1;

        while($count--){
            $code .= $chars[rand(0, $size)];
        }

        return $code;
    }
}