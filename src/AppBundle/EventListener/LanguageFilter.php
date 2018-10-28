<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 10/17/2018
 * Time: 9:02 PM
 */

namespace AppBundle\EventListener;


use AppBundle\Service\LocalLanguage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LanguageFilter implements EventSubscriberInterface
{
    /**
     * @var LocalLanguage
     */
    private $language;

    public function __construct(LocalLanguage $language)
    {
        $this->language = $language;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();
        /*
         * $controller passed can be either a class or a Closure.
         * This is not usual in Symfony but it may happen.
         * If it is a class, it comes in array format
         */
        if (!is_array($controller)) {
            return;
        }

        $lang = $event->getRequest()->get('lang');
        if($lang != null)
            $this->language->setLang($lang);

    }

    public static function getSubscribedEvents()
    {
        return array(
            //TODO place other filters if needed
            //KernelEvents::CONTROLLER => 'onKernelController',
        );
    }
}