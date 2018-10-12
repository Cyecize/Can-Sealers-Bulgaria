<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/8/2018
 * Time: 12:45 PM
 */

namespace AppBundle\Service;


class FirstRunServiceImpl implements FirstRunService
{

    /**
     * @var RoleService
     */
    private $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * Creates initial db content like roles, main category, languages
     */
    public function initDb(): void
    {
        $this->roleService->createRolesIfNotExist();
    }
}