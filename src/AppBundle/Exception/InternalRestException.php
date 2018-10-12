<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/5/2018
 * Time: 4:02 PM
 */

namespace AppBundle\Exception;


class InternalRestException extends \Exception implements RestException
{
    public function __construct(string $message = "", int $code = 0)
    {
        parent::__construct($message, $code, null);
    }
}