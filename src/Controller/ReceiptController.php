<?php


namespace App\Controller;


use App\Exception\NotFoundException;
use App\Service\LocalLanguage;
use App\Service\ReceiptService;
use App\Utils\Pageable;
use App\ViewModel\BrowseRecipesViewModel;
use App\ViewModel\ReceiptDetailsViewModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReceiptController extends BaseController
{

    private $receiptService;

    public function __construct(LocalLanguage $language, ReceiptService $receiptService)
    {
        parent::__construct($language);
        $this->receiptService = $receiptService;
    }

    /**
     * @Route("/recipes", name="show_recipes")
     * @param Request $request
     * @return Response
     */
    public function showRecipes(Request $request): Response
    {
        $recipes = $this->receiptService->findAll(new Pageable($request));

        return $this->render('menu/browse-recipes.html.twig', [
            'viewModel' => new BrowseRecipesViewModel($recipes)
        ]);
    }

    /**
     * @Route("/receipt/{id}", name="receipt_details", defaults={"id"=-1})
     * @param $id
     * @return Response
     * @throws NotFoundException
     */
    public function showProductAction($id)
    {
        $receipt = $this->receiptService->findOneById($id);
        if ($receipt == null)
            throw new NotFoundException($this->dictionary->productNotFound());

        return $this->render("menu/receipt.html.twig", [
            'viewModel' => new ReceiptDetailsViewModel($receipt)
        ]);
    }
}