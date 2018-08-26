<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="admin")
 */
class Admin extends User
{
    /**
     * Admin constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->addRole(User::ROLE_ADMIN);
    }
}