<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="buyer")
 */
class Buyer extends User
{
    /**
     * Buyer constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->addRole(User::ROLE_BUYER);
    }
}