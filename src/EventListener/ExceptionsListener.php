<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 8/5/2018
 * Time: 12:30 AM
 */

namespace App\EventListener;

use App\Constants\Config;
use App\Exception\NotFoundException;
use App\Exception\RestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig\Environment;

class ExceptionsListener
{
    /**
     * @var Environment
     */
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Registered in services.yaml.
     *
     * @param ExceptionEvent $event
     */
    public function onException(ExceptionEvent $event) {
        if (!boolval($_ENV[Config::ENV_ENABLE_ERROR_HANDLING])) {
            return;
        }

        if (!$this->onKernelException($event)
            && !$this->onNotFoundException($event)
            && !$this->onRestException($event)) {
            return;
        }
    }


    private function onKernelException(ExceptionEvent $event) : bool
    {
        $exception = $event->getThrowable();

        if ($exception instanceof AccessDeniedHttpException) {
            $response = new Response();
            $response->setContent($this->twig->render("exceptions/error401.html.twig", [
                'exception' => $exception
            ]));
            $event->setResponse($response);

            return true;
        }


        return false;
    }

    private function onNotFoundException(ExceptionEvent $event) : bool
    {
        $exception = $event->getThrowable();
        if ($exception instanceof NotFoundException) {
            $response = new Response();
            $response->setContent($this->twig->render("exceptions/not-found-exception.html.twig", [
                'exception' => $event->getThrowable(),
            ]));
            $event->setResponse($response);

            return true;
        }

        if ($exception instanceof NotFoundHttpException) {
            $response = new Response();
            $response->setContent($this->twig->render("exceptions/error404.html.twig", []));
            $event->setResponse($response);

            return true;
        }

        return false;
    }

    private function onRestException(ExceptionEvent $event) : bool
    {
        $exception = $event->getThrowable();
        if (!$exception instanceof RestException) {
            return false;
        }

        $code = $exception->getCode();
        $responseData = [
            'code' => $code,
            'message' => $exception->getMessage()
        ];
        $event->setResponse(new JsonResponse($responseData, $code));

        return true;
    }
}