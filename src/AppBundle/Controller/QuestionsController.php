<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/23/2018
 * Time: 1:30 PM
 */

namespace AppBundle\Controller;

use AppBundle\BindingModel\QuestionBindingModel;
use AppBundle\Exception\NotFoundException;
use AppBundle\Form\QuestionType;
use AppBundle\Service\LocalLanguage;
use AppBundle\Service\MailingService;
use AppBundle\Service\NotificationSendingService;
use AppBundle\Service\QuestionService;
use AppBundle\Service\UserService;
use AppBundle\Utils\Pageable;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class QuestionsController extends BaseController
{
    private const QUESTION_NOT_FOUND_MSG = "Question not found!";

    /**
     * @var QuestionService
     */
    private $questionService;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var MailingService
     */
    private $mailingService;

    /**
     * @var NotificationSendingService
     */
    private $notificationService;

    public function __construct(LocalLanguage $language, QuestionService $questionService, UserService $userService,
                                MailingService $mailSendingService, NotificationSendingService $notificationSendingService)
    {
        parent::__construct($language);
        $this->questionService = $questionService;
        $this->userService = $userService;
        $this->mailingService = $mailSendingService;
        $this->notificationService = $notificationSendingService;
    }

    /**
     * @Route("/contacts", name = "contacts" )
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \AppBundle\Exception\InternalRestException
     */
    public function contactsAction(Request $request)
    {
        $bindingModel = new QuestionBindingModel();
        $form = $this->createForm(QuestionType::class, $bindingModel);
        $form->handleRequest($request);

        $info = null;
        if ($form->isSubmitted() && count($this->validate($bindingModel)) < 1) {
            $this->validateToken($request);
            $question = $this->questionService->createQuestion($bindingModel, $this->userService->findOneById($bindingModel->getUserId()));
            $this->mailingService->sendQuestionToAdmins($question);
            $this->notificationService->onQuestion($question);
            $info = $this->dictionary->yourMessageWasSent();
            $bindingModel = new QuestionBindingModel();
        }

        escape:
        return $this->render(":menu:contacts.html.twig", [
            'form1' => $form->createView(),
            'about' => $request->get('about') . "\r\n",
            'questionModel' => $bindingModel,
            'info' => $info
        ]);
    }

    /**
     * @Route("/admin/questions/view/{qid}", name="question_details", defaults={"qid"="-1"})
     * @Security("has_role('ROLE_ADMIN')")
     * @param $qid
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws NotFoundException
     */
    public function questionDetailsAction($qid)
    {
        $question = $this->questionService->findOneById(intval($qid));
        if ($question == null)
            throw new NotFoundException(self::QUESTION_NOT_FOUND_MSG);
        return $this->render('default/question-details.html.twig', [
            'question' => $question
        ]);
    }

    /**
     * @Route("/admin/questions/observe", name="observe_questions")
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function observeQuestionsAction(Request $request)
    {
        return $this->render('admins/questions/all-questions.html.twig', [
            'page' => $this->questionService->findAll(new Pageable($request))
        ]);
    }

}