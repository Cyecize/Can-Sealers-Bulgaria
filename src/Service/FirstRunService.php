<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/8/2018
 * Time: 12:45 PM
 */

namespace App\Service;


interface FirstRunService
{
    /**
     * Creates initial db content like roles, main category, languages
     */
    public function initDb(): void;
}