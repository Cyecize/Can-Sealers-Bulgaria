<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/25/2018
 * Time: 6:04 PM
 */

namespace App\Service;


use App\Entity\PasswordRecovery;
use App\Entity\User;
use App\Repository\PasswordRecoveryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class PasswordRecoveryServiceImpl implements PasswordRecoveryService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var PasswordRecoveryRepository|ObjectRepository
     */
    private $passwordRecoveryRepo;

    /**
     * PasswordRecoveryDbManager constructor.
     * @param $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->passwordRecoveryRepo = $entityManager->getRepository(PasswordRecovery::class);
    }

    /**
     * @param PasswordRecovery $passwordRecovery
     */
    public function removePasswordRecovery(PasswordRecovery $passwordRecovery): void
    {
        $this->entityManager->remove($passwordRecovery);
        $this->entityManager->flush();
    }

    /**
     * @param User $user
     * @return PasswordRecovery
     */
    public function addPasswordRecovery(User $user): PasswordRecovery
    {
        $passRec = $this->findOneByUser($user);
        if ($passRec != null)
            return $passRec;
        $passRec = new PasswordRecovery();
        $passRec->setUser($user);
        $this->entityManager->persist($passRec);
        $this->entityManager->flush();
        return $passRec;
    }

    /**
     * @param int $id
     * @return PasswordRecovery|null
     */
    public function findOneById(int $id): ?PasswordRecovery
    {
        return $this->passwordRecoveryRepo->findOneBy(array('id' => $id));
    }

    /**
     * @param string $token
     * @return PasswordRecovery|null
     */
    public function findOneByToken(string $token): ?PasswordRecovery
    {
        return $this->passwordRecoveryRepo->findOneBy(array('token' => $token));
    }

    /**
     * @param User $user
     * @return PasswordRecovery|null
     */
    public function findOneByUser(User $user): ?PasswordRecovery
    {
        return $this->passwordRecoveryRepo->findOneBy(array('user' => $user));
    }
}