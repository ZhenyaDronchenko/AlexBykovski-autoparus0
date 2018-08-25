<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="seller")
 */
class Seller extends User
{
    /**
     * Seller constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->addRole(User::ROLE_SELLER);
    }
}