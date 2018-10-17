<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/11/2018
 * Time: 3:50 PM
 */

namespace AppBundle\Service;


use AppBundle\BindingModel\ChangePasswordBindingModel;
use AppBundle\BindingModel\PersonalInfoBindingModel;
use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use AppBundle\Exception\IllegalArgumentException;
use AppBundle\Form\PersonalInfoType;

interface UserService
{
    /**
     * @param User $user
     */
    function save(User $user) : void ;

    /**
     * @param User $user
     * @param Role $role
     * @throws IllegalArgumentException
     */
    function removeRole(User $user, Role $role) : void ;

    /**
     * @param User $user
     * @param Role $role
     * @throws IllegalArgumentException
     */

    function addRole(User $user, Role $role): void;

    /**
     * @param User $user
     * @param ChangePasswordBindingModel $bindingModel
     * @param bool $verify
     * @throws IllegalArgumentException
     */
    function changePassword(User $user, ChangePasswordBindingModel $bindingModel, bool $verify = true) : void ;

    /**
     * @param User $user
     * @param PersonalInfoBindingModel $bindingModel
     */
    function editUserInfo(User $user, PersonalInfoBindingModel $bindingModel) : void ;

    /**
     * @param User $user
     * @throws IllegalArgumentException
     */
    function removeAccount(User $user) : void ;

    /**
     * @param int $id
     * @return User
     */
    function findOneById(int $id): ?User;

    /**
     * @param string $username
     * @return User
     */
    function findOneByUsername(string $username): ?User;

    /**
     * @return User[]
     */
    function findAll(): array;

    /**
     * @param string $role
     * @return User[]
     */
    function findByRole(string $role): array;
}