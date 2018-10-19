<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/18/2018
 * Time: 4:24 PM
 */

namespace AppBundle\Controller;

use AppBundle\BindingModel\ImageBindingModel;
use AppBundle\Exception\InternalRestException;
use AppBundle\Exception\NotFoundException;
use AppBundle\Form\ImageType;
use AppBundle\Service\GalleryService;
use AppBundle\Service\ImageService;
use AppBundle\Service\LocalLanguage;
use AppBundle\Service\ProductService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GalleryController extends BaseController
{
    private const INVALID_IMAGE_MSG = "Please provide a valid image and text!";
    private const FORM_NOT_SUBMITTED_MSG = "Form not submitted!";
    private const IMAGE_NOT_FOUND_MSG = "Image not found!";

    /**
     * @var GalleryService
     */
    private $galleryService;

    /**
     * @var ImageService
     */
    private $imageService;

    /**
     * @var ProductService
     */
    private $productService;

    public function __construct(LocalLanguage $language, GalleryService $galleryService, ImageService $imageService, ProductService $productService)
    {
        parent::__construct($language);
        $this->galleryService = $galleryService;
        $this->imageService = $imageService;
        $this->productService = $productService;
    }

    /**
     * @Route("/admin/product/{prodId}/gallery/add", name="add_image_to_product", defaults={"prodId"=null}, methods={"POST"})
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @param $prodId
     * @return JsonResponse
     * @throws NotFoundException
     * @throws \AppBundle\Exception\InternalRestException
     */
    public function addImageToGalleryAction(Request $request, $prodId)
    {
        $product = $this->productService->findOneById($prodId, true);
        if ($product == null)
            throw new NotFoundException($this->dictionary->productNotFound());

        $bindingModel = new ImageBindingModel();
        $form = $this->createForm(ImageType::class, $bindingModel);
        $form->handleRequest($request);
        if (!$form->isSubmitted())
            throw new InternalRestException(self::FORM_NOT_SUBMITTED_MSG);
        $this->validateToken($request);
        $altMsg = $request->get('altMsg');
        if (count($this->validate($bindingModel)) > 0 || $altMsg == null)
            throw new InternalRestException(self::INVALID_IMAGE_MSG);
        $gallery = $this->galleryService->createIfNotExists($product);
        $this->imageService->createImage($gallery, $bindingModel, $altMsg);
        return new JsonResponse(array('message' => 'Image Added'));
    }

    /**
     * @Route("/admin/images/remove/{id}", name="remove_image_from_gallery", defaults={"id" = null})
     * @Security("has_role('ROLE_ADMIN')")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws NotFoundException
     */
    public function removeImageAction($id)
    {
        $image = $this->imageService->findOneById($id);
        if ($image == null)
            throw new NotFoundException(self::IMAGE_NOT_FOUND_MSG);
        $imgProd = $image->getGallery()->getProduct();
        $this->imageService->removeImage($image);
        return $this->redirectToRoute("edit_product", [
            'id' => $imgProd->getId()
        ]);
    }
}