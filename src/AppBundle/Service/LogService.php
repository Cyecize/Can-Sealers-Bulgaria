<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/23/2018
 * Time: 2:46 PM
 */

namespace AppBundle\Service;


use AppBundle\Utils\Page;
use AppBundle\Utils\Pageable;

interface LogService
{
    /**
     * @param string $location
     * @param string $message
     */
    public function log(string $location, string $message) : void ;

    /**
     * @param Pageable $pageable
     * @return Page
     */
    public function findAll(Pageable $pageable) : Page;
}