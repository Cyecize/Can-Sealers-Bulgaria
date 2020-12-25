<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/8/2018
 * Time: 12:45 PM
 */

namespace App\Service;


class FirstRunServiceImpl implements FirstRunService
{

    /**
     * @var RoleService
     */
    private $roleService;

    /**
     * @var LanguageDbService
     */
    private $langService;

    public function __construct(RoleService $roleService, LanguageDbService $langService)
    {
        $this->roleService = $roleService;
        $this->langService = $langService;
    }

    /**
     * Creates initial db content like roles, main category, languages
     */
    public function initDb(): void
    {
        $this->roleService->createRolesIfNotExist();
        $this->langService->initLanguages();
    }
}