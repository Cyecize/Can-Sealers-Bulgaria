<?php

namespace App\Controller;

use App\Constants\Config;
use App\Service\CharacteristicsYamlService;
use App\Service\LocalLanguage;
use App\Service\ProductService;
use App\Utils\Pageable;
use App\Utils\PageRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends BaseController
{
    /**
     * @var ProductService
     */
    private $productService;

    public function __construct(LocalLanguage $language, ProductService $productService)
    {
        parent::__construct($language);
        $this->productService = $productService;
    }

    /**
     * @Route("/", name="homepage" )
     * @param Request $request
     * @param CharacteristicsYamlService $characteristicsService
     * @return Response
     */
    public function indexAction(Request $request, CharacteristicsYamlService $characteristicsService)
    {
        // replace this example code with whatever you need
        $err = $request->get('error');
        return $this->render('default/index.html.twig', [
            'characteristics' => $characteristicsService->getCharacteristics(),
            'err' => $err, //error
            'products' => $this->productService
                ->findAll(new PageRequest(1, Config::MAX_PAGE_SIZE))->getElements(),
        ]);
    }

    /**
     * @Route("/privacy-policy", name="privacy_policy")
     */
    public function privacyPolicyAction(){
        return $this->render('default/privacy-policy.html.twig');
    }
}
