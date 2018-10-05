<?php

namespace App\Entity\Interfaces;


interface VariableInterface
{
    public function replaceVariables($string);
}