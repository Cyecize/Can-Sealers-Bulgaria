<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/23/2018
 * Time: 1:33 PM
 */

namespace App\Service;

use App\BindingModel\QuestionBindingModel;
use App\Entity\Question;
use App\Entity\User;
use App\Repository\QuestionRepository;
use App\Utils\ModelMapper;
use App\Utils\Page;
use App\Utils\Pageable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class QuestionServiceImpl implements QuestionService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ObjectRepository|QuestionRepository
     */
    private $questionRepo;

    /**
     * @var ModelMapper
     */
    private $modelMapper;

    public function __construct(EntityManagerInterface $entityManager, ModelMapper $modelMapper)
    {
        $this->entityManager = $entityManager;
        $this->modelMapper = $modelMapper;
        $this->questionRepo = $entityManager->getRepository(Question::class);
    }

    public function createQuestion(QuestionBindingModel $bindingModel, User $user = null): Question
    {
        $question = $this->modelMapper->map($bindingModel, Question::class);
        $question->setUser($user);
        $this->entityManager->persist($question);
        $this->entityManager->flush();
        return $question;
    }

    public function findOneById(int $id): ?Question
    {
        return $this->questionRepo->find($id);
    }

    public function findAll(Pageable $pageable): Page
    {
        return $this->questionRepo->findPage($pageable);
    }

    public function all(): array
    {
        return $this->questionRepo->findBy(array(), array('date' => 'DESC'));
    }
}