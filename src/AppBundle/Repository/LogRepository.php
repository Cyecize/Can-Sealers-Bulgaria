<?php

namespace AppBundle\Repository;

use AppBundle\Utils\Page;
use AppBundle\Utils\Pageable;

/**
 * LogRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class LogRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllPage(Pageable $pageable) : Page{
        return new Page($this->createQueryBuilder('l')->orderBy('l.id', 'DESC'), $pageable);
    }
}
