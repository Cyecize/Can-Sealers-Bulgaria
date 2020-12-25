<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 10/17/2018
 * Time: 9:02 PM
 */

namespace App\EventListener;

use App\Entity\User;
use App\Service\LocalLanguage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class LanguageFilter implements EventSubscriberInterface
{
    /**
     * @var LocalLanguage
     */
    private $language;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(LocalLanguage $language,
                                EntityManagerInterface $entityManager,
                                TokenStorageInterface $tokenStorage )
    {
        $this->language = $language;
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Registered by extending EventSubscriberInterface
     *
     * @param ControllerEvent $event
     */
    public function onKernelController(ControllerEvent $event)
    {
        $controller = $event->getController();
        $loggedUser = $this->tokenStorage->getToken()->getUser();

        /*
         * $controller passed can be either a class or a Closure.
         * This is not usual in Symfony but it may happen.
         * If it is a class, it comes in array format
         */
        if (!is_array($controller)
            || $loggedUser == null
            || gettype($loggedUser) == 'string'
            || $loggedUser->getLanguage()->getLocaleName() == $this->language->getLocalLang())
            return;

        $user = $this->entityManager->getRepository(User::class)->find($loggedUser->getId());
        $user->setLanguage($this->language->findLanguageByName($this->language->getLocalLang()));
        $this->entityManager->merge($user);
        $this->entityManager->flush();
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => 'onKernelController',
        );
    }
}