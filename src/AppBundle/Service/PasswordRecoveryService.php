<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/25/2018
 * Time: 6:03 PM
 */

namespace AppBundle\Service;


use AppBundle\Entity\PasswordRecovery;
use AppBundle\Entity\User;

interface PasswordRecoveryService
{
    /**
     * @param PasswordRecovery $passwordRecovery
     */
    public function removePasswordRecovery(PasswordRecovery $passwordRecovery): void;

    /**
     * @param User $user
     * @return PasswordRecovery
     */
    public function addPasswordRecovery(User $user): PasswordRecovery;

    /**
     * @param int $id
     * @return PasswordRecovery|null
     */
    public function findOneById(int $id): ?PasswordRecovery;

    /**
     * @param string $token
     * @return PasswordRecovery|null
     */
    public function findOneByToken(string $token): ?PasswordRecovery;

    /**
     * @param User $user
     * @return PasswordRecovery|null
     */
    public function findOneByUser(User $user): ?PasswordRecovery;
}