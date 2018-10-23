<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 10/15/2018
 * Time: 8:13 PM
 */

namespace AppBundle\Controller;

use AppBundle\Service\LocalLanguage;
use AppBundle\Service\LogService;
use AppBundle\Utils\Pageable;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LogController extends BaseController
{
    /**
     * @var LogService
     */
    private $logService;

    public function __construct(LocalLanguage $language, LogService $logDb)
    {
        parent::__construct($language);
        $this->logService = $logDb;
    }

    /**
     * @Route("/admin/logs/show", name="show_logs")
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showLogsAction(Request $request)
    {
        return $this->render('admins/logs/browse-logs.html.twig', [
            'page' => $this->logService->findAll(new Pageable($request))
        ]);
    }

}