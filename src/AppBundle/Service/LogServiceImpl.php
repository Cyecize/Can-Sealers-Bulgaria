<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/23/2018
 * Time: 2:46 PM
 */

namespace AppBundle\Service;


use AppBundle\Entity\Log;
use AppBundle\Utils\Pageable;
use AppBundle\Utils\Page;
use Doctrine\ORM\EntityManagerInterface;

class LogServiceImpl implements LogService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManger;

    /**
     * @var \AppBundle\Repository\LogRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    private $logRepo;

    public function __construct(EntityManagerInterface $entityManger)
    {
        $this->entityManger = $entityManger;
        $this->logRepo = $entityManger->getRepository(Log::class);
    }


    public function log(string $location, string $message): void
    {
        $log = new Log();
        $log->setLocation($location);
        $log->setMessage($message);
        $this->entityManger->persist($log);
        $this->entityManger->flush();
    }

    public function findAll(Pageable $pageable): Page
    {
        return $this->logRepo->findAllPage($pageable);
    }
}