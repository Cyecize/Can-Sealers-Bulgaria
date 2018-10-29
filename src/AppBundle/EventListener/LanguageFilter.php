<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 10/17/2018
 * Time: 9:02 PM
 */

namespace AppBundle\EventListener;

use AppBundle\Constants\Config;
use AppBundle\Entity\User;
use AppBundle\Service\LocalLanguage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class LanguageFilter implements EventSubscriberInterface
{
    /**
     * @var LocalLanguage
     */
    private $language;

    /**
     * @var User
     */
    private $loggedUser;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(LocalLanguage $language, TokenStorage $tokenStorage, EntityManagerInterface $entityManager)
    {
        $this->language = $language;
        $this->loggedUser = $tokenStorage->getToken()->getUser();
        $this->entityManager = $entityManager;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();
        /*
         * $controller passed can be either a class or a Closure.
         * This is not usual in Symfony but it may happen.
         * If it is a class, it comes in array format
         */
        if (!is_array($controller) || $this->loggedUser == null || gettype($this->loggedUser) == 'string' || $this->loggedUser->getLanguage()->getLocaleName() == $this->language->getLocalLang())
            return;
        $user = $this->entityManager->getRepository(User::class)->find($this->loggedUser->getId());
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