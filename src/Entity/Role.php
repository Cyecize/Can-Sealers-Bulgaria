<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Role
 *
 * Used to extend  \Symfony\Component\Security\Core\Role\Role. This is now deprecated! and will be removed in symfony 5.
 *
 * @ORM\Table(name="roles")
 * @ORM\Entity(repositoryClass="App\Repository\RoleRepository")
 */
class Role
{
    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="role", type="string", length=50, unique=true)
     */
    private $role;

    public function __construct(string $role)
    {
        $this->setRole($role);
    }

    /**
     * Get id
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set role
     * @param string $role
     * @return Role
     */
    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }

    /**
     * Get role
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    public function __toString(): string
    {
        return $this->role;
    }
}

