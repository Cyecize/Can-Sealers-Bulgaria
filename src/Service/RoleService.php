<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/8/2018
 * Time: 12:47 PM
 */

namespace App\Service;

use App\Entity\Role;
use App\Exception\IllegalArgumentException;

interface RoleService
{
    function createRolesIfNotExist() : void ;

    /**
     * @param string $roleName
     * @return Role
     * @throws IllegalArgumentException
     */
    function createRole(string $roleName) : ?Role;

    /**
     * @param int $id
     * @return Role
     */
    function findById(int $id):  ?Role;

    /**
     * @param string $roleName
     * @return Role
     */
    function findByRoleName(string  $roleName) : ?Role;

    /**
     * @return Role[]
     */
    function findAll() : array ;
}