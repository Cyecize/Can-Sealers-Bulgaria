<?php

namespace AppBundle\Controller;

use AppBundle\Constants\ProductType;
use AppBundle\Entity\Notification;
use AppBundle\Form\NotificationType;
use AppBundle\Service\CharacteristicsYamlService;
use AppBundle\Service\LocalLanguage;
use AppBundle\Service\ProductService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request, CharacteristicsYamlService $characteristicsService)
    {
        // replace this example code with whatever you need
        $err = $request->get('error');

        return $this->render(':default:index.html.twig', [
            'characteristics' => $characteristicsService->getCharacteristics(),
            'err' => $err, //error
            'products' => $this->productService->findByProductType(ProductType::PRODUCT),
        ]);
    }

    /**
     * @Route("/contacts", name = "contacts" )
     */
    public function contactsAction(Request $request, \Swift_Mailer $mailer)
    {
        //$admins = $this->getDoctrine()->getRepository(User::class)->findBy(array("adminStatus"=>true));
        $notification = new Notification();
        $notification->setAbout($request->get('about') . "\r\n");

        $form = $this->createForm(NotificationType::class, $notification);
        $form->handleRequest($request);

//        if($form->isSubmitted()){
//            if(!$notification->isValid()){
//                $error = "Не оставяйте полетата празни!";
//                goto  escape;
//            }
//
//            $entityManager = $this->getDoctrine()->getManager();
//            foreach ($admins as $admin){
//                $notification->setTargetId($admin->getId());
//                $entityManager->persist($notification);
//
//                //send mail
//                $message = (new \Swift_Message($notification->createSummary()))
//                    ->setFrom([Constants::$mailer => Constants::$mailerAs])
//                    ->setTo($admin->getEmail())
//                    ->setBody($this->renderView(
//                        ':Mailing:new-question.html.twig',
//                        array('notification'=>$notification)
//                    ),
//                        'text/html');
//
//                try {$mailer->send($message);} catch (Swift_TransportException $e){}
//                //end sendEmail
//            }
//            $entityManager->flush();
//            $error = "success";
//            $notification = new Notification();
//        }


        escape:
        return $this->render(":menu:contacts.html.twig", [
            'form' => $form->createView(),
            'about'=>$request->get('about') . "\r\n",
        ]);
    }

}
