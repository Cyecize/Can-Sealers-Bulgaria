<?php

namespace AppBundle\Service;

use AppBundle\BindingModel\ChangePasswordBindingModel;
use AppBundle\Constants\Roles;
use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use AppBundle\Exception\IllegalArgumentException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Exception\UnsupportedException;

class UserServiceImpl implements UserService
{
    private const CANNOT_ALTER_ADMIN = "Cannot remove admin privileges!";
    private const USER_HAS_THAT_ROLE = "User already has that role!";
    private const USER_DOES_NOT_HAVE_ROLE = "User doesn't have that role!";

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var \AppBundle\Repository\UserRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    private $userRepo;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;


    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->userRepo = $entityManager->getRepository(User::class);
    }

    function save(User $user): void
    {
        $this->entityManager->merge($user);
        $this->entityManager->flush();
    }

    function removeRole(User $user, Role $role): void
    {
        if ($user->hasRole(Roles::ROLE_ADMIN) && $role->getRole() == Roles::ROLE_ADMIN)
            throw new IllegalArgumentException(self::CANNOT_ALTER_ADMIN);
        if (!$user->hasRole($role->getRole()))
            throw new IllegalArgumentException(self::USER_DOES_NOT_HAVE_ROLE);
        $user->removeRole($role);
        $this->save($user);
    }

    function addRole(User $user, Role $role): void
    {
        if ($user->hasRole($role->getRole()))
            throw new IllegalArgumentException(self::USER_HAS_THAT_ROLE);
        $user->addRole($role);
        $this->save($user);
    }

    function changePassword(User $user, ChangePasswordBindingModel $bindingModel, bool $verify = true): void
    {
        if ($verify && !password_verify($bindingModel->getOldPassword(), $user->getPassword()))
            throw new IllegalArgumentException("passwordIsIncorrect");
        $user->setPassword($this->passwordEncoder->encodePassword($user, $bindingModel->getNewPassword()));
        $this->entityManager->merge($user);
        $this->entityManager->flush();
    }

    function removeAccount(User $user): void
    {
        // TODO: Implement removeAccount() method.
        throw new UnsupportedException("TODO: Implement this later");
    }

    function findOneById(int $id): ?User
    {
        return $this->userRepo->find($id);
    }

    function findOneByUsername(string $username): ?User
    {
        return $this->userRepo->findOneBy(array('username'=>$username));
    }

    function findAll(): array
    {
       return $this->userRepo->findAll();
    }

    function findByRole(string $role): array
    {
        return $this->userRepo->findByRoleName($role);
    }

}