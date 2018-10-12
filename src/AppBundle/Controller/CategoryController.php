<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/9/2018
 * Time: 3:01 PM
 */

namespace AppBundle\Controller;

use AppBundle\Entity\ProductCategory;
use AppBundle\Exception\NotFoundException;
use AppBundle\Service\CategoryService;
use AppBundle\Service\LocalLanguage;
use AppBundle\Service\ProductService;
use AppBundle\Utils\Pageable;
use AppBundle\ViewModel\BrowseProductsViewModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends BaseController
{
    /**
     * @var CategoryService
     */
    private $categoryService;

    /**
     * @var ProductService
     */
    private $productService;

    public function __construct(LocalLanguage $language, CategoryService $categoryService, ProductService $productService)
    {
        parent::__construct($language);
        $this->categoryService = $categoryService;
        $this->productService = $productService;
    }

    /**
     * @Route("/categories", name="show_categories")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showCategoriesAction(Request $request)
    {
        $categories = $this->categoryService->findMain();
        $cat = new ProductCategory();
        $cat->setCategoryName($this->dictionary->all());
        $cat->setLatinName($this->dictionary->all());
        return $this->render(":menu:browse-products.html.twig", [
            'viewModel' => new BrowseProductsViewModel($cat, $this->productService->findAll(new Pageable($request)), $categories),
        ]);
    }

    /**
     * @Route("/category/{catName}", name="category_details", defaults={"catName"=""})
     * @param Request $request
     * @param $catName
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws NotFoundException
     */
    public function categoryDetailsAction(Request $request, $catName)
    {
        $category = $this->categoryService->findOneByName($catName);
        if ($category == null)
            throw new NotFoundException(sprintf($this->dictionary->categoryNotFoundFormat(), $catName));

        return $this->render("menu/browse-products.html.twig",
            [
                'viewModel' => new BrowseProductsViewModel($category, $this->productService->findByCategoryRecursive($category, new Pageable($request)), $category->getSubcategories()),
            ]);
    }
}