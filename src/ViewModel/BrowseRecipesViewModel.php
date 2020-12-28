<?php


namespace App\ViewModel;


use App\Entity\Receipt;
use App\Utils\Page;

class BrowseRecipesViewModel
{
    /**
     * @var Receipt[]
     */
    private $products;

    private $productPage;

    public function __construct(Page $recipesPage)
    {
        $this->products = $recipesPage->getElements();
        $this->productPage = $recipesPage;
    }

    /**
     * @return Receipt[]
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * @return Page
     */
    public function getProductPage(): Page
    {
        return $this->productPage;
    }
}