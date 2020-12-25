<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/10/2018
 * Time: 11:10 AM
 */

namespace App\Exception;


class NotFoundException extends \Exception implements InternalException
{
    public function __construct(string $message = "")
    {
        parent::__construct($message, 404, null);
    }
}